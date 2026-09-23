@extends('layouts.app')

@section('breadcrumb', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

<style>
    /* ── Header ── */
    .mu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .mu-title {
        font-size: 22px;
        font-weight: 800;
        color: #1B2559;
        margin: 0 0 4px;
        letter-spacing: -0.01em;
    }
    .mu-subtitle {
        color: #64748B;
        margin: 0;
        font-size: 13.5px;
    }
    .mu-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* ── Buttons ── */
    .mu-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        padding: 10px 18px;
        cursor: pointer;
        transition: all .18s ease;
        text-decoration: none;
    }
    .mu-btn svg {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
        flex-shrink: 0;
    }
    .mu-btn-primary {
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #fff;
        box-shadow: 0 2px 8px rgba(2,62,138,.2);
    }
    .mu-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(2,62,138,.3);
        color: #fff;
    }
    .mu-btn-outline {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .mu-btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    /* ── Summary Cards (Soft Harmonized Design System) ── */
    .mu-card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 860px) {
        .mu-card-grid {
            grid-template-columns: 1fr;
        }
    }
    .mu-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(15,23,42,.03), 0 10px 15px -3px rgba(15,23,42,.02);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        transition: transform .2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .mu-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    .mu-card.blue::before  { background: linear-gradient(90deg, #023E8A, #0081AB); }
    .mu-card.green::before { background: linear-gradient(90deg, #059669, #10B981); }
    .mu-card.amber::before { background: linear-gradient(90deg, #D97706, #F59E0B); }
    .mu-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -4px rgba(15,23,42,.08);
    }
    .mu-card-info {
        display: flex;
        flex-direction: column;
    }
    .mu-card-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748B;
        margin: 0 0 4px;
    }
    .mu-card-value {
        font-size: 28px;
        font-weight: 800;
        color: #1B2559 !important;
        margin: 0 0 2px;
        line-height: 1;
    }
    .mu-card-note {
        font-size: 12px;
        color: #94A3B8;
        margin: 0;
    }
    .mu-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .mu-card-icon svg {
        width: 22px;
        height: 22px;
        stroke-width: 2.1;
    }
    .mu-card-icon.blue {
        background: linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12));
        color: #0081AB;
    }
    .mu-card-icon.green {
        background: linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06));
        color: #2E9E5B;
    }
    .mu-card-icon.amber {
        background: linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06));
        color: #E8A317;
    }

    /* ── Main Surface Card ── */
    .mu-surface-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eef1f5;
        box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 6px 18px rgba(15,23,42,.04);
        overflow: hidden;
    }

    /* Header Bar Soft */
    .mu-section-header {
        background: linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09));
        padding: 16px 22px;
        border-bottom: 1px solid #eef1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .mu-section-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mu-section-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12));
        color: #023E8A;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .mu-section-header-icon svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }
    .mu-section-header-title {
        font-size: 15px;
        font-weight: 800;
        color: #023E8A;
        margin: 0;
    }
    .mu-section-header-sub {
        font-size: 12px;
        color: #64748B;
        margin: 2px 0 0;
    }
    .mu-count-badge {
        font-size: 12px;
        font-weight: 700;
        color: #0081AB;
        background: rgba(0,129,171,.1);
        padding: 5px 12px;
        border-radius: 999px;
        border: 1px solid rgba(0,129,171,.18);
    }

    /* ── Filter & Search Toolbar (SATU BARIS SEJAJAR) ── */
    .mu-toolbar {
        padding: 14px 22px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
        width: 100%;
        box-sizing: border-box;
    }
    .mu-search-box {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1 1 280px;
        max-width: 360px;
        min-width: 200px;
    }
    .mu-search-box svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        color: #94a3b8;
        pointer-events: none;
        z-index: 2;
    }
    .mu-search-input {
        width: 100% !important;
        padding-top: 9px !important;
        padding-bottom: 9px !important;
        padding-right: 14px !important;
        padding-left: 38px !important;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        background: #f8fafc;
        color: #0f172a;
        transition: all .15s ease;
        box-sizing: border-box;
    }
    .mu-search-input:focus {
        outline: none;
        border-color: #0081AB;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,129,171,.12);
    }
    .mu-select-filter {
        padding: 9px 30px 9px 12px;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        background: #f8fafc;
        color: #334155;
        font-weight: 500;
        cursor: pointer;
        transition: border-color .15s ease, box-shadow .15s ease;
        flex: 0 0 170px;
        width: 170px;
        box-sizing: border-box;
    }
    .mu-select-filter:focus {
        outline: none;
        border-color: #0081AB;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,129,171,.12);
    }
    .mu-reset-btn {
        font-size: 12px;
        color: #C0392B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
        border-radius: 8px;
        background: rgba(192,57,43,.08);
        font-weight: 600;
        flex-shrink: 0;
        white-space: nowrap;
        transition: background .15s ease;
    }
    .mu-reset-btn:hover {
        background: rgba(192,57,43,.16);
    }

    @media (max-width: 820px) {
        .mu-toolbar {
            flex-wrap: wrap;
        }
        .mu-search-box {
            flex: 1 1 100%;
            max-width: 100%;
        }
        .mu-select-filter {
            flex: 1 1 calc(50% - 6px);
            width: auto;
        }
    }

    /* ── Table Styling ── */
    .mu-table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    .mu-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .mu-table thead th {
        background: #F8FAFC;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #64748B;
        padding: 13px 20px;
        border-bottom: 1px solid #eef1f5;
        white-space: nowrap;
    }
    .mu-table tbody td {
        padding: 14px 20px;
        font-size: 13.3px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }
    .mu-table tbody tr {
        transition: background .12s ease;
    }
    .mu-table tbody tr:hover {
        background: rgba(0,129,171,.03);
    }
    .mu-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── User Avatar & Info ── */
    .mu-user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mu-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.14));
        color: #023E8A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        border: 1px solid rgba(2,62,138,.15);
    }
    .mu-user-name {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mu-user-you {
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 4px;
        background: rgba(0,129,171,.12);
        color: #0081AB;
        text-transform: uppercase;
    }
    .mu-user-sub {
        font-size: 11.5px;
        color: #94A3B8;
        margin: 2px 0 0;
    }

    /* ── Role Badges ── */
    .mu-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .mu-role-super_admin {
        background: rgba(2,62,138,.1);
        color: #023E8A;
        border: 1px solid rgba(2,62,138,.2);
    }
    .mu-role-pemasaran {
        background: rgba(0,129,171,.1);
        color: #0081AB;
        border: 1px solid rgba(0,129,171,.2);
    }
    .mu-role-pengelola {
        background: rgba(46,158,91,.1);
        color: #2E9E5B;
        border: 1px solid rgba(46,158,91,.2);
    }
    .mu-role-manajemen {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
    }

    /* ── Status Styles ── */
    .mu-status-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mu-badge-pending {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(232,163,23,.12);
        color: #92660F;
        border: 1px solid rgba(232,163,23,.25);
    }
    .mu-btn-resend {
        background: none;
        border: none;
        color: #0081AB;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: color .15s ease;
    }
    .mu-btn-resend:hover {
        color: #023E8A;
        text-decoration: underline;
    }
    .mu-btn-resend svg {
        width: 12px;
        height: 12px;
    }

    /* Soft Toggle Switch */
    .mu-toggle {
        width: 38px;
        height: 22px;
        border-radius: 999px;
        background: #cbd5e1;
        border: none;
        position: relative;
        cursor: pointer;
        transition: background .2s ease;
        padding: 0;
        flex-shrink: 0;
    }
    .mu-toggle.on {
        background: #2E9E5B;
    }
    .mu-toggle:disabled {
        opacity: .45;
        cursor: not-allowed;
    }
    .mu-toggle-knob {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
        transition: left .2s ease;
    }
    .mu-toggle.on .mu-toggle-knob {
        left: 18px;
    }
    .mu-status-text {
        font-size: 12px;
        font-weight: 600;
    }
    .mu-status-text.active { color: #2E9E5B; }
    .mu-status-text.inactive { color: #94A3B8; }

    /* ── Action Buttons ── */
    .mu-actions {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .mu-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: rgba(0,129,171,.08);
        color: #0081AB;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
    }
    .mu-icon-btn:hover {
        background: rgba(0,129,171,.16);
        color: #023E8A;
        border-color: rgba(0,129,171,.2);
    }
    .mu-icon-btn svg {
        width: 14px;
        height: 14px;
        stroke-width: 2.2;
    }

    .mu-del-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: rgba(192,57,43,.08);
        color: #C0392B;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
    }
    .mu-del-btn:hover {
        background: rgba(192,57,43,.16);
        border-color: rgba(192,57,43,.2);
    }
    .mu-del-btn svg {
        width: 14px;
        height: 14px;
        stroke-width: 2.2;
    }
    .mu-del-btn:disabled {
        opacity: .3;
        cursor: not-allowed;
    }

    /* ── Empty State ── */
    .mu-empty-box {
        text-align: center;
        padding: 50px 20px;
        color: #64748B;
    }
    .mu-empty-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #F1F5F9;
        color: #94A3B8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    .mu-empty-icon svg {
        width: 24px;
        height: 24px;
        stroke-width: 2;
    }
    .mu-empty-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
    }
    .mu-empty-text {
        font-size: 13px;
        color: #64748B;
        margin: 0 0 14px;
    }

    /* ── Modal Dialogs ── */
    .mu-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,.45);
        backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
        z-index: 60;
        padding: 16px;
    }
    .mu-modal-overlay.show {
        display: flex;
    }
    .mu-modal {
        background: #fff;
        border-radius: 18px;
        width: 480px;
        max-width: 100%;
        box-shadow: 0 20px 50px rgba(15,23,42,.22);
        overflow: hidden;
        animation: modalPop .2s cubic-bezier(.4,0,.2,1);
    }
    .mu-modal-header {
        background: linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09));
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #eef1f5;
    }
    .mu-modal-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .mu-modal-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12));
        color: #023E8A;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mu-modal-header-icon svg {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
    }
    .mu-modal-header h3 {
        margin: 0;
        font-size: 15.5px;
        font-weight: 800;
        color: #023E8A;
    }
    .mu-modal-close {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #94A3B8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
    }
    .mu-modal-close:hover {
        background: #F1F5F9;
        color: #1e293b;
    }
    .mu-modal-close svg {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
    }

    /* Modal Tabs */
    .mu-modal-tabs {
        display: flex;
        background: #F8FAFC;
        padding: 6px 12px;
        border-bottom: 1px solid #eef1f5;
        gap: 6px;
    }
    .mu-modal-tab {
        flex: 1;
        padding: 8px 12px;
        border-radius: 8px;
        background: transparent;
        border: none;
        font-size: 12.8px;
        font-weight: 700;
        color: #64748B;
        cursor: pointer;
        transition: all .15s ease;
    }
    .mu-modal-tab.active {
        background: #fff;
        color: #0081AB;
        box-shadow: 0 1px 3px rgba(15,23,42,.08);
    }

    /* Modal Form Body */
    .mu-modal-body {
        padding: 22px;
    }
    .mu-field {
        margin-bottom: 16px;
    }
    .mu-field:last-of-type {
        margin-bottom: 0;
    }
    .mu-field label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }
    .mu-field input,
    .mu-field select {
        width: 100%;
        padding: 10px 14px;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        background: #fff;
        color: #0f172a;
        transition: border-color .15s ease, box-shadow .15s ease;
        box-sizing: border-box;
    }
    .mu-field input:focus,
    .mu-field select:focus {
        outline: none;
        border-color: #0081AB;
        box-shadow: 0 0 0 3px rgba(0,129,171,.12);
    }
    .mu-field input[readonly],
    .mu-field input[disabled] {
        background: #F8FAFC;
        color: #64748B;
        cursor: not-allowed;
    }
    .mu-hint-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 9px;
        padding: 10px 14px;
        font-size: 12px;
        color: #64748B;
        margin-top: 12px;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .mu-hint-box svg {
        width: 15px;
        height: 15px;
        color: #0081AB;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .mu-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid #F1F5F9;
    }
</style>

{{-- Page Header --}}
<div class="mu-header">
    <div>
        <h1 class="mu-title">Manajemen User</h1>
        <p class="mu-subtitle">Kelola data akun, peran pengguna, dan otorisasi akses sistem SPKLU</p>
    </div>
    <div class="mu-header-actions">
        <button class="mu-btn mu-btn-primary" onclick="openModal('modal-tambah-user')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah User
        </button>
    </div>
</div>

{{-- Summary Cards (Soft Harmonized Design) --}}
<div class="mu-card-grid">
    <div class="mu-card blue">
        <div class="mu-card-info">
            <p class="mu-card-label">Total Pengguna</p>
            <p class="mu-card-value">{{ $totalTerdaftar }}</p>
            <p class="mu-card-note">Pengguna terdaftar di sistem</p>
        </div>
        <div class="mu-card-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>

    <div class="mu-card green">
        <div class="mu-card-info">
            <p class="mu-card-label">Pengguna Aktif</p>
            <p class="mu-card-value">{{ $totalAktif }}</p>
            <p class="mu-card-note">Dapat login &amp; mengakses sistem</p>
        </div>
        <div class="mu-card-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
    </div>

    <div class="mu-card amber">
        <div class="mu-card-info">
            <p class="mu-card-label">Menunggu Aktivasi</p>
            <p class="mu-card-value">{{ $totalPending }}</p>
            <p class="mu-card-note">Undangan terkirim / pending OTP</p>
        </div>
        <div class="mu-card-icon amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
    </div>
</div>

{{-- Main Surface Card --}}
<div class="mu-surface-card">

    {{-- Section Header Bar --}}
    <div class="mu-section-header">
        <div class="mu-section-header-left">
            <div class="mu-section-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <h2 class="mu-section-header-title">Daftar Pengguna</h2>
                <p class="mu-section-header-sub">Rincian data akun, perizinan role, dan status keaktifan user</p>
            </div>
        </div>
        <div class="mu-count-badge">
            {{ $users->total() }} User Terdata
        </div>
    </div>

    {{-- Search & Filter Toolbar (SATU BARIS SEJAJAR) --}}
    <form method="GET" action="{{ route('manajemen-user.index') }}" class="mu-toolbar">
        <div class="mu-search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="mu-search-input">
        </div>

        <select name="role" class="mu-select-filter" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="pemasaran" {{ request('role') === 'pemasaran' ? 'selected' : '' }}>Pemasaran</option>
            <option value="pengelola" {{ request('role') === 'pengelola' ? 'selected' : '' }}>Pengelola</option>
            <option value="manajemen" {{ request('role') === 'manajemen' ? 'selected' : '' }}>Manajemen</option>
        </select>

        <select name="status" class="mu-select-filter" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Aktivasi</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        @if (request()->filled('search') || request()->filled('role') || request()->filled('status'))
            <a href="{{ route('manajemen-user.index') }}" class="mu-reset-btn" title="Reset semua filter">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Reset
            </a>
        @endif
    </form>

    {{-- Table of Users --}}
    <div class="mu-table-responsive">
        <table class="mu-table">
            <thead>
                <tr>
                    <th>Nama &amp; Pengguna</th>
                    <th>Email</th>
                    <th>Role / Akses</th>
                    <th>Status</th>
                    <th>Terakhir Login</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                <tr>
                    <td>
                        <div class="mu-user-cell">
                            <div class="mu-avatar">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="mu-user-name">
                                    {{ $u->name }}
                                    @if ($u->id === auth()->id())
                                        <span class="mu-user-you">Anda</span>
                                    @endif
                                </p>
                                <p class="mu-user-sub">Terdaftar {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 500; color: #475569;">{{ $u->email }}</span>
                    </td>
                    <td>
                        <span class="mu-role-badge mu-role-{{ $u->role }}">
                            {{ ucwords(str_replace('_', ' ', $u->role)) }}
                        </span>
                    </td>
                    <td>
                        <div class="mu-status-cell">
                            @if ($u->status->value === 'pending')
                                <span class="mu-badge-pending">Menunggu Aktivasi</span>
                                <form method="POST" action="{{ route('manajemen-user.resend-invitation', $u) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="mu-btn-resend" title="Kirim ulang email undangan">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                        Kirim Ulang
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('manajemen-user.toggle-status', $u) }}"
                                      data-confirm="Status {{ $u->name }} akan diubah menjadi {{ $u->status->value === 'active' ? 'Nonaktif' : 'Aktif' }}."
                                      data-confirm-title="Ubah status pengguna ini?">
                                    @csrf
                                    <button type="submit" class="mu-toggle {{ $u->status->value === 'active' ? 'on' : '' }}"
                                            {{ $u->id === auth()->id() ? 'disabled' : '' }}
                                            title="{{ $u->id === auth()->id() ? 'Tidak dapat menonaktifkan akun sendiri' : 'Klik untuk mengubah status' }}">
                                        <span class="mu-toggle-knob"></span>
                                    </button>
                                </form>
                                <span class="mu-status-text {{ $u->status->value === 'active' ? 'active' : 'inactive' }}">
                                    {{ $u->status->value === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span style="color: #64748B; font-size: 13px;">
                            {{ $u->last_login_at ? $u->last_login_at->translatedFormat('d M Y, H:i') : '—' }}
                        </span>
                    </td>
                    <td>
                        <div class="mu-actions" style="justify-content: flex-end;">
                            <button type="button" class="mu-icon-btn btn-edit-user" title="Edit Data Pengguna"
                                    data-id="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-email="{{ $u->email }}"
                                    data-role="{{ $u->role }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                            </button>

                            <form method="POST" action="{{ route('manajemen-user.destroy', $u) }}"
                                  data-confirm="Pengguna &quot;{{ $u->name }}&quot; ({{ $u->email }}) akan dihapus permanen dari sistem."
                                  data-confirm-title="Hapus Pengguna?"
                                  data-confirm-type="danger">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mu-del-btn" title="{{ $u->id === auth()->id() ? 'Tidak bisa menghapus akun sendiri' : 'Hapus Pengguna' }}" {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="mu-empty-box">
                            <div class="mu-empty-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            </div>
                            <p class="mu-empty-title">Tidak ada pengguna ditemukan</p>
                            <p class="mu-empty-text">Coba periksa kembali kata kunci pencarian atau sesuaikan filter Anda.</p>
                            @if (request()->filled('search') || request()->filled('role') || request()->filled('status'))
                                <a href="{{ route('manajemen-user.index') }}" class="mu-btn mu-btn-outline" style="display:inline-flex;">Reset Filter</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
        <div style="padding: 16px 22px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <p style="margin: 0; font-size: 13px; color: #64748B;">
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
            </p>
            <div>{{ $users->links() }}</div>
        </div>
    @endif

</div>

{{-- =========================================================
     MODAL TAMBAH USER (Undang via Email & Buat Akun Langsung)
========================================================= --}}
<div id="modal-tambah-user" class="mu-modal-overlay" onclick="handleBackdropClick(event, 'modal-tambah-user')">
    <div class="mu-modal" onclick="event.stopPropagation()">
        <div class="mu-modal-header">
            <div class="mu-modal-header-left">
                <div class="mu-modal-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <h3>Tambah Pengguna Baru</h3>
            </div>
            <button type="button" class="mu-modal-close" onclick="closeModal('modal-tambah-user')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="mu-modal-tabs">
            <button type="button" class="mu-modal-tab active" data-tab="invite" onclick="switchUserTab('invite')">Undang via Email</button>
            <button type="button" class="mu-modal-tab" data-tab="direct" onclick="switchUserTab('direct')">Buat Akun Langsung</button>
        </div>

        <form method="POST" action="{{ route('manajemen-user.store') }}" class="mu-modal-body">
            @csrf
            <input type="hidden" name="mode" id="mode-input" value="invite">

            <div class="mu-field">
                <label for="new-name">Nama Lengkap</label>
                <input type="text" id="new-name" name="name" required placeholder="Contoh: Raden Adityawarman">
            </div>

            <div class="mu-field">
                <label for="new-email">Alamat Email</label>
                <input type="email" id="new-email" name="email" required placeholder="email@pln.co.id">
            </div>

            <div class="mu-field">
                <label for="new-role">Tingkat Hak Akses (Role)</label>
                <select id="new-role" name="role" required>
                    <option value="super_admin">Super Admin (Akses Penuh Seluruh Modul)</option>
                    <option value="pemasaran">Pemasaran (Analisis Kelayakan &amp; Peringkat)</option>
                    <option value="pengelola">Pengelola (Operasional &amp; Monitoring SPKLU)</option>
                    <option value="manajemen">Manajemen (Laporan &amp; Ringkasan Eksekutif)</option>
                </select>
            </div>

            <div id="panel-invite">
                <div class="mu-hint-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>User akan menerima email undangan resmi dan dapat melakukan aktivasi melalui Google OAuth atau kode OTP.</span>
                </div>
            </div>

            <div id="panel-direct" style="display:none;">
                <div class="mu-field" style="margin-top: 14px;">
                    <label for="new-password">Kata Sandi (Opsional)</label>
                    <input type="password" id="new-password" name="password" minlength="8" placeholder="Kosongkan untuk generate kata sandi acak otomatis">
                </div>
                <div class="mu-hint-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>Akun langsung aktif. Saat pertama login, user akan diminta verifikasi OTP lalu diarahkan memperbarui kata sandi.</span>
                </div>
            </div>

            <div class="mu-modal-footer">
                <button type="button" class="mu-btn mu-btn-outline" onclick="closeModal('modal-tambah-user')">Batal</button>
                <button type="submit" class="mu-btn mu-btn-primary" id="submit-user-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Undangan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================
     MODAL EDIT USER
========================================================= --}}
<div id="modal-edit-user" class="mu-modal-overlay" onclick="handleBackdropClick(event, 'modal-edit-user')">
    <div class="mu-modal" onclick="event.stopPropagation()">
        <div class="mu-modal-header">
            <div class="mu-modal-header-left">
                <div class="mu-modal-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                </div>
                <div>
                    <h3>Edit Data Pengguna</h3>
                    <p id="edit-user-email-display" style="margin: 2px 0 0; font-size: 11.5px; color: #64748B;"></p>
                </div>
            </div>
            <button type="button" class="mu-modal-close" onclick="closeModal('modal-edit-user')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="edit-user-form" method="POST" action="" class="mu-modal-body">
            @csrf
            @method('PATCH')

            <div class="mu-field">
                <label for="edit-user-name">Nama Lengkap</label>
                <input type="text" id="edit-user-name" name="name" required>
            </div>

            <div class="mu-field">
                <label for="edit-user-role">Tingkat Hak Akses (Role)</label>
                <select id="edit-user-role" name="role" required>
                    <option value="super_admin">Super Admin (Akses Penuh Seluruh Modul)</option>
                    <option value="pemasaran">Pemasaran (Analisis Kelayakan &amp; Peringkat)</option>
                    <option value="pengelola">Pengelola (Operasional &amp; Monitoring SPKLU)</option>
                    <option value="manajemen">Manajemen (Laporan &amp; Ringkasan Eksekutif)</option>
                </select>
            </div>

            <div class="mu-hint-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Perubahan role langsung berlaku pada sesi login pengguna berikutnya. Alamat email tidak dapat diubah demi audit integritas.</span>
            </div>

            <div class="mu-modal-footer">
                <button type="button" class="mu-btn mu-btn-outline" onclick="closeModal('modal-edit-user')">Batal</button>
                <button type="submit" class="mu-btn mu-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

function handleBackdropClick(e, id) {
    if (e.target.id === id) {
        closeModal(id);
    }
}

function switchUserTab(tab) {
    document.querySelectorAll('.mu-modal-tab').forEach(b => b.classList.toggle('active', b.dataset.tab === tab));
    document.getElementById('panel-invite').style.display = tab === 'invite' ? 'block' : 'none';
    document.getElementById('panel-direct').style.display = tab === 'direct' ? 'block' : 'none';
    document.getElementById('mode-input').value = tab;
    
    const submitBtn = document.getElementById('submit-user-btn');
    if (tab === 'invite') {
        submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" style="margin-right:6px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Kirim Undangan';
    } else {
        submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" style="margin-right:6px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Buat Akun';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-edit-user').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            var userName = this.getAttribute('data-name');
            var userEmail = this.getAttribute('data-email');
            var userRole = this.getAttribute('data-role');

            var form = document.getElementById('edit-user-form');
            form.action = '/manajemen-user/' + userId;
            document.getElementById('edit-user-name').value = userName;
            document.getElementById('edit-user-role').value = userRole;
            document.getElementById('edit-user-email-display').textContent = userEmail;

            openModal('modal-edit-user');
        });
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('modal-tambah-user');
        closeModal('modal-edit-user');
    }
});

@if (session('generated_account'))
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        title: 'Akun Berhasil Dibuat',
        html: `
            <p style="font-size:13.5px; color:#64748B; margin-bottom:14px;">Simpan kredensial ini sekarang — kata sandi tidak akan ditampilkan lagi.</p>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; text-align:left;">
                <p style="margin:0 0 8px; font-size:13px; color:#334155;"><strong>Email:</strong> {{ session('generated_account')['email'] }}</p>
                <p style="margin:0; font-size:13px; color:#334155;"><strong>Kata Sandi:</strong> <code style="font-size:14px; background:#fff; padding:3px 8px; border-radius:6px; border:1px solid #cbd5e1; color:#023E8A; font-weight:700;">{{ session('generated_account')['password'] }}</code></p>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Sudah Disalin, Tutup',
        confirmButtonColor: '#0081AB',
        allowOutsideClick: false,
    });
});
@endif
</script>

@endsection