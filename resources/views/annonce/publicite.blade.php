@extends('layouts.app')

@section('content')
<div class="ads-container">
    <h2 class="ads-title">Liste des Publicités</h2>

    @if($publicite == null)
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <p>Aucune publicité disponible pour le moment</p>
        </div>
    @else
        <div class="ads-grid">
            @foreach($publicite as $pub)
                <div class="ad-card">
                    <div class="ad-card-header">
                        <img src="{{ asset('assets/img/' . $pub->moyen_comm . '.png') }}"
                             alt="Image de {{ $pub->moyen_comm }}"
                             class="ad-icon">
                        <span class="ad-type">{{ $pub->moyen_comm }}</span>
                    </div>
                    <div class="ad-card-body">
                        <div class="ad-info">
                            <span class="info-label">Département</span>
                            <span class="info-value">{{ $pub->departement }}</span>
                        </div>
                        <div class="ad-info">
                            <span class="info-label">Poste</span>
                            <span class="info-value">{{ $pub->poste }}</span>
                        </div>
                        <div class="ad-info">
                            <span class="info-label">Date</span>
                            <span class="info-value">{{ $pub->date_publicite }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

<style>
.ads-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.ads-title {
    font-size: 2.5rem;
    color: #1a237e;
    margin-bottom: 2rem;
    text-align: center;
    font-weight: 700;
    position: relative;
}

.ads-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #1a237e, #3949ab);
    border-radius: 2px;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    background: #f5f5f5;
    border-radius: 1rem;
    margin: 2rem 0;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #666;
    font-size: 1.2rem;
}

.ads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    padding: 1rem 0;
}

.ad-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.ad-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.ad-card-header {
    background: linear-gradient(135deg, #1a237e, #3949ab);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
}

.ad-icon {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid white;
    background: white;
    padding: 5px;
}

.ad-type {
    font-size: 1.2rem;
    font-weight: 600;
}

.ad-card-body {
    padding: 1.5rem;
}

.ad-info {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.info-label {
    color: #666;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.3rem;
}

.info-value {
    color: #333;
    font-size: 1.1rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .ads-container {
        padding: 0 1rem;
    }

    .ads-title {
        font-size: 2rem;
    }

    .ads-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

/* Animation d'entrée */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ad-card {
    animation: fadeUp 0.5s ease-out forwards;
}

.ad-card:nth-child(2) { animation-delay: 0.1s; }
.ad-card:nth-child(3) { animation-delay: 0.2s; }
.ad-card:nth-child(4) { animation-delay: 0.3s; }
</style>
