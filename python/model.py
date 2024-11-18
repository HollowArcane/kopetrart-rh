import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder, MultiLabelBinarizer
import pickle
import re

from flask import Flask, request, jsonify
import pickle

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    cv_data = request.get_json()
    predictions = predict_cv(cv_data)
    
    return jsonify(predictions)

class CVPreprocessor:
    def __init__(self):
        self.le_poste = LabelEncoder()
        self.mlb_interests = MultiLabelBinarizer()
        self.mlb_qualities = MultiLabelBinarizer()
        self.education_levels = {'BACC': 1, 'Diploma': 2, 'Licence': 3, 'Master': 4}
        
    def fit(self, df):
        """Fit all encoders on training data"""
        # Clean and prepare data before fitting
        df['interests'] = df['interests'].str.split(', ')
        df['qualities'] = df['qualities'].str.split(', ')
        
        self.le_poste.fit(df['poste'].unique())
        self.mlb_interests.fit(df['interests'])
        self.mlb_qualities.fit(df['qualities'])
        
    def transform_single_cv(self, data_row):
        """Transform a single CV entry"""
        # Convert single row to DataFrame
        df = pd.DataFrame([data_row])
        
        # Process date
        df['date_depot_dossier'] = pd.to_datetime(df['date_depot_dossier'], format='%Y-%m-%d')
        df['month'] = df['date_depot_dossier'].dt.month
        df['year'] = df['date_depot_dossier'].dt.year
        
        # Split lists if they're not already lists
        df['interests'] = df['interests'].apply(lambda x: x.split(', ') if isinstance(x, str) else x)
        df['qualities'] = df['qualities'].apply(lambda x: x.split(', ') if isinstance(x, str) else x)
        df['educations'] = df['educations'].apply(lambda x: x.split(', ') if isinstance(x, str) else x)
        
        # Process experience
        df['total_experience_months'] = df['experiences'].apply(self._extract_experience_months)
        
        try:
            # Encode features using fitted encoders
            poste_encoded = self.le_poste.transform([data_row['poste']])
            
            # Check if 'interests' and 'qualities' keys exist in data_row
            interests = data_row.get('interests', [])
            qualities = data_row.get('qualities', [])
            
            interests_encoded = self.mlb_interests.transform([interests])
            qualities_encoded = self.mlb_qualities.transform([qualities])


            # Create feature DataFrames
            features_dict = {
                'poste_encoded': poste_encoded[0],
                'month': self._extract_experience_months(data_row['experiences']),  
                'year': df['year'].iloc[0],  
                'education_level': self._get_highest_education(data_row['educations']),
                'total_experience_months': self._extract_experience_months(data_row['experiences'])
            }


            # Add encoded interests
            for i, col in enumerate(self.mlb_interests.classes_):
                features_dict[f'interest_{i}'] = interests_encoded[0][i]

            # Add encoded qualities
            for i, col in enumerate(self.mlb_qualities.classes_):
                features_dict[f'quality_{i}'] = qualities_encoded[0][i]

            return pd.DataFrame([features_dict])
        except Exception as e:
            print(f"Error transforming CV: {e}")
            print(f"Data row: {data_row}")
            raise


    def _extract_experience_months(self, exp_list):
        total_months = 0
        for exp in exp_list:
            months = exp.get('month_duration', 0)
            if isinstance(months, str):
                total_months += float(months)
            else:
                total_months += months
        return total_months
    
    def _get_highest_education(self, edu_list):
        try:
            highest = 0
            for edu in edu_list:
                level = self.education_levels.get(edu, 0)
                highest = max(highest, level)
            return highest
        except Exception as e:
            print(f"Error processing education {edu_list}: {e}")
            return 0

def load_and_clean_data(csv_path):
    """Load and clean the CSV data"""
    # Read the CSV file
    df = pd.read_csv(csv_path)
    
    # Remove any row that contains column headers
    df = df[df['date_depot_dossier'] != 'date_depot_dossier']
    
    # Reset index after cleaning
    df = df.reset_index(drop=True)
    
    # Convert target variables to numeric
    df['adequate'] = pd.to_numeric(df['adequate'], errors='coerce')
    df['potentiel'] = pd.to_numeric(df['potentiel'], errors='coerce')
    
    # Drop rows with NaN values in target variables
    df = df.dropna(subset=['adequate', 'potentiel'])
    
    return df
def train_models(csv_path):
    """Train models and save preprocessor"""
    df = load_and_clean_data(csv_path)

    preprocessor = CVPreprocessor()
    preprocessor.fit(df)

    # Save preprocessor
    with open('cv_preprocessor.pkl', 'wb') as f:
        pickle.dump(preprocessor, f)

    X = []
    y_adequate = []
    y_potentiel = []

    for idx, row in df.iterrows():
        try:
            processed_row = preprocessor.transform_single_cv(row.to_dict())
            X.append(processed_row)
            y_adequate.append(df.loc[idx, 'adequate'])
            y_potentiel.append(df.loc[idx, 'potentiel'])
        except Exception as e:
            print(f"Error processing row {idx}: {e}")
            continue

    X = pd.concat(X, ignore_index=True)

    # Train models
    rf_adequate = RandomForestClassifier(
        n_estimators=100, random_state=42
    )
    rf_adequate.fit(X, y_adequate)

    rf_potentiel = RandomForestClassifier(
        n_estimators=100, random_state=42
    )
    rf_potentiel.fit(X, y_potentiel)

    models = {
        'adequate': rf_adequate,
        'potentiel': rf_potentiel
    }

    # Save models
    with open('cv_models.pkl', 'wb') as f:
        pickle.dump(models, f)

    return models, preprocessor

def predict_cv(cv_data):
    """Make predictions for a new CV"""
    try:
        with open('cv_preprocessor.pkl', 'rb') as f:
            preprocessor = pickle.load(f)

        with open('cv_models.pkl', 'rb') as f:
            models = pickle.load(f)

        X_new = preprocessor.transform_single_cv(cv_data)

        predictions = {}

        # Predict adequate
        pred_adequate = models['adequate'].predict(X_new)[0]
        prob_adequate = models['adequate'].predict_proba(X_new)[0]
        predictions['adequate'] = {
            'prediction': int(pred_adequate),
            'probability': prob_adequate.tolist()
        }

        # Predict potentiel
        total_experience_months = X_new['total_experience_months'].iloc[0]
        if total_experience_months < 12:
            pred_potentiel = 0
        else:
            pred_potentiel = models['potentiel'].predict(X_new)[0]
        prob_potentiel = models['potentiel'].predict_proba(X_new)[0]
        predictions['potentiel'] = {
            'prediction': int(pred_potentiel),
            'probability': prob_potentiel.tolist()
        }

        return predictions
    except Exception as e:
        print(f"Error making prediction: {e}")
        raise
    
  
if __name__ == "__main__":
    with open('cv_preprocessor.pkl', 'rb') as f:
        preprocessor = pickle.load(f)
    with open('cv_models.pkl', 'rb') as f:
        models = pickle.load(f)
    app.run(host='0.0.0.0', port=5000)