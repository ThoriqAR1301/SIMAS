@props(['status', 'size' => 'sm'])

@include('pengajuan.partials.status-badge', ['status' => $status, 'size' => $size])
