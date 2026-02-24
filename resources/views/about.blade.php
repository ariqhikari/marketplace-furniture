@extends('layouts.admin')

@section('title', 'Tentang Aplikasi')

@section('content')
<h4 class="fw-bold mb-4"><i class="fas fa-info-circle me-2"></i>Tentang Aplikasi</h4>

{{-- App Info --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <small class="text-muted">Aplikasi</small>
                <h5 class="fw-bold mb-0">{{ $appName }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body">
                <small class="text-muted">Laravel</small>
                <h5 class="fw-bold mb-0">v{{ $laravelVersion }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-info border-4">
            <div class="card-body">
                <small class="text-muted">PHP</small>
                <h5 class="fw-bold mb-0">v{{ $phpVersion }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <small class="text-muted">Server</small>
                <h5 class="fw-bold mb-0 fs-6">{{ $serverSoftware }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- System Monitor --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-memory me-1"></i> System Monitor
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="ps-3 text-muted">Memory Usage</td>
                            <td class="fw-bold">{{ $memoryUsage }} MB</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Memory Peak</td>
                            <td class="fw-bold">{{ $memoryPeak }} MB</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Memory Limit</td>
                            <td class="fw-bold">{{ $memoryLimit }}</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Max Execution Time</td>
                            <td class="fw-bold">{{ $maxExecutionTime }}s</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Database Size</td>
                            <td class="fw-bold">{{ $dbSize }} MB</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">DB Query Time</td>
                            <td class="fw-bold">{{ $queryTime }} ms</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Storage Size</td>
                            <td class="fw-bold">{{ $storageSize }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Dependencies --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-box me-1"></i> Dependencies (composer.json)
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <h6 class="fw-bold text-muted small">Production</h6>
                <table class="table table-sm">
                    <tbody>
                        @foreach($dependencies as $package => $version)
                        <tr>
                            <td><code>{{ $package }}</code></td>
                            <td>{{ $version }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h6 class="fw-bold text-muted small mt-3">Development</h6>
                <table class="table table-sm mb-0">
                    <tbody>
                        @foreach($devDependencies as $package => $version)
                        <tr>
                            <td><code>{{ $package }}</code></td>
                            <td>{{ $version }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Recent Logs --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-file-alt me-1"></i> Log Terbaru (20 baris terakhir)
    </div>
    <div class="card-body p-0">
        @if(count($recentLogs) > 0)
        <div style="max-height: 400px; overflow-y: auto;">
            <pre class="mb-0 p-3 bg-dark text-light" style="font-size: 12px; white-space: pre-wrap; word-wrap: break-word;">@foreach($recentLogs as $line){{ $line }}
@endforeach</pre>
        </div>
        @else
        <div class="p-4 text-center text-muted">
            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
            <p class="mb-0">Tidak ada log.</p>
        </div>
        @endif
    </div>
</div>
@endsection