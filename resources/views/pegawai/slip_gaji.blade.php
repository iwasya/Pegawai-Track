@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">📄 Slip Gaji Saya</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>No Slip</th>
                    <th>Periode</th>
                    <th>Tanggal Cetak</th>
                    <th>Total Pendapatan</th>
                    <th>Total Potongan</th>
                    <th>Gaji Bersih</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gajis as $gaji)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gaji->no_slip }}</td>
                        <td>{{ $gaji->bulan_periode }}</td>
                        <td>{{ \Carbon\Carbon::parse($gaji->tanggal_cetak)->translatedFormat('d F Y') }}</td>
                        <td>Rp{{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                        <td><strong>Rp{{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</strong></td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalSlip{{ $gaji->id_gaji }}">
                                Lihat Slip
                            </button>
                        </td>
                    </tr>

                    {{-- Modal per slip --}}
                    <div class="modal fade" id="modalSlip{{ $gaji->id_gaji }}" tabindex="-1" role="dialog" aria-labelledby="modalSlipLabel{{ $gaji->id_gaji }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalSlipLabel{{ $gaji->id_gaji }}">Slip Gaji - {{ $gaji->no_slip }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="font-family: sans-serif; font-size: 14px;">
                                    <h5 class="text-center mb-3">Slip Gaji Pegawai</h5>
                                    <p><strong>Nama:</strong> {{ $gaji->pegawai->nama_lengkap }}</p>
                                    <p><strong>Jabatan:</strong> {{ $gaji->pegawai->jabatan->nama_jabatan ?? '-' }}</p>
                                    <p><strong>Periode:</strong> {{ $gaji->bulan_periode }}</p>
                                    <p><strong>No Slip:</strong> {{ $gaji->no_slip }}</p>
                                    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::parse($gaji->tanggal_cetak)->translatedFormat('d F Y') }}</p>

                                    <table class="table table-sm mt-3" style="border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <th style="border:1px solid #000; padding:8px;">Total Pendapatan</th>
                                                <td class="text-right" style="border:1px solid #000; padding:8px;">Rp{{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th style="border:1px solid #000; padding:8px;">Total Potongan</th>
                                                <td class="text-right" style="border:1px solid #000; padding:8px;">Rp{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th style="border:1px solid #000; padding:8px;">Gaji Bersih</th>
                                                <td class="text-right font-weight-bold" style="border:1px solid #000; padding:8px;">Rp{{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h6 class="mt-4">📋 Rekap Kehadiran</h6>
                                    <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                        <tbody>
                                            <tr><td>Hadir</td><td class="text-right">{{ $gaji->absen_hadir ?? 0 }} hari</td></tr>
                                            <tr><td>Izin</td><td class="text-right">{{ $gaji->absen_izin ?? 0 }} hari</td></tr>
                                            <tr><td>Sakit</td><td class="text-right">{{ $gaji->absen_sakit ?? 0 }} hari</td></tr>
                                            <tr><td>Cuti</td><td class="text-right">{{ $gaji->absen_cuti ?? 0 }} hari</td></tr>
                                            <tr><td>Alpha</td><td class="text-right">{{ $gaji->absen_alpha ?? 0 }} hari</td></tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-5 text-right">
                                        <p>Disetujui oleh,</p>
                                        <br><br>
                                        <p><strong>Bagian Keuangan</strong></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('gaji.saya.download', $gaji->id_gaji) }}" class="btn btn-success" target="_blank">
                                        <i class="fa fa-download"></i> Download PDF
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8">Belum ada data slip gaji.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('styles')
    {{-- Jika kamu butuh CSS tambahan, tambahkan di sini --}}
@endsection

@section('scripts')
    {{-- Pastikan sudah include jQuery dan Bootstrap JS di layout, 
         tapi kalau belum, tambahkan script berikut: --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
