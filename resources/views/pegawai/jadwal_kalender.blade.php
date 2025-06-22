@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="bi bi-calendar2-week"> Kalender Jadwal Saya</h4>

    {{-- Filter bulan dan tahun --}}
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="bulan" class="form-select">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" class="form-select">
                @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                    <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    {{-- Tabel kalender --}}
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle calendar-table" style="font-size: 13px;">
            <thead class="table-dark">
                <tr style="font-size: 12px;">
                    <th>Senin</th>
                    <th>Selasa</th>
                    <th>Rabu</th>
                    <th>Kamis</th>
                    <th>Jumat</th>
                    <th class="text-primary">Sabtu</th>
                    <th class="text-danger">Minggu</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use Illuminate\Support\Carbon;

                    $start = Carbon::create($tahun, $bulan, 1);
                    $end = $start->copy()->endOfMonth();
                    $current = $start->copy()->startOfWeek();
                    $today = now()->toDateString();
                @endphp

                @while ($current <= $end)
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $tglStr = $current->toDateString();
                            $jadwals = $jadwalMap[$tglStr] ?? null;

                            $isOtherMonth = $current->month != $bulan;
                            $isToday = $tglStr === $today;
                            $namaLibur = $tanggalMerah[$tglStr] ?? null;

                            $classes = [];
                            if ($isOtherMonth) $classes[] = 'bg-light text-muted';
                            if ($isToday) $classes[] = 'border border-primary border-2';
                            if ($namaLibur) $classes[] = 'bg-danger text-white';

                            $cellClass = implode(' ', $classes);
                        @endphp
                        <td class="{{ $cellClass }}" style="height: 100px; padding: 4px; vertical-align: top;">
                            {{-- Tanggal --}}
                            <div class="text-end small" style="font-size: 11px;">
                                <strong>{{ $current->day }}</strong>
                            </div>

                            {{-- Tanggal Merah --}}
                            @if($namaLibur)
                                <div class="text-start small fw-bold text-white" style="font-size: 10px;">
                                    🎉 {{ $namaLibur }}
                                </div>
                            @endif

                            {{-- Jadwal Kerja --}}
                            @if($jadwals)
                                @foreach($jadwals as $j)
                                    <div class="badge d-block text-wrap mb-1 text-white"
                                        style="background-color:
                                            {{ match($j->shift) {
                                                'Pagi' => '#0d6efd',
                                                'Siang' => '#ffc107',
                                                'Sore' => '#fd7e14',
                                                'Malam' => '#6f42c1',
                                                default => '#198754',
                                            } }};
                                            font-size: 10px;
                                            line-height: 1.1;">
                                        {{ $j->shift }}<br>
                                        <small>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                        @php $current->addDay(); @endphp
                    @endfor
                </tr>
                @endwhile
            </tbody>
        </table>
    </div>
</div>
@endsection
