@extends('layouts.app')

@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    /* ========================================================
       DASHBOARD PREMIUM STYLES
       ======================================================== */
    :root {
        --dsh-primary: #023E8A;
        --dsh-cyan: #0081AB;
        --dsh-green: #2E9E5B;
        --dsh-amber: #E8A317;
        --dsh-red: #C0392B;
        --dsh-purple: #7C3AED;
    }

    /* ===== Welcome / Top Bar ===== */
    .dsh-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E2E8F0;
    }
    .dsh-welcome-title {
        font-size: 24px;
        font-weight: 800;
        color: #1B2559;
        letter-spacing: -0.025em;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dsh-welcome-subtitle {
        color: #64748B;
        font-size: 13.5px;
        margin: 0;
        font-weight: 500;
    }
    .dsh-topbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dsh-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(46, 158, 91, 0.08);
        border: 1px solid rgba(46, 158, 91, 0.25);
        color: #15803D;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 999px;
    }
    .dsh-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22C55E;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        animation: dshPulse 2s infinite;
    }
    @keyframes dshPulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
        70% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .dsh-date-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #334155;
        font-weight: 600;
        background: #FFFFFF;
        padding: 6px 14px;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .dsh-date-chip svg {
        width: 15px;
        height: 15px;
        color: #0081AB;
    }

    /* ===== KPI Metric Cards ===== */
    .dsh-card-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) {
        .dsh-card-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .dsh-card-grid { grid-template-columns: 1fr; }
    }

    .dsh-kpi-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03), 0 10px 15px -3px rgba(15, 23, 42, 0.02);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .dsh-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
    }
    .dsh-kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    .dsh-kpi-card.blue::before   { background: linear-gradient(90deg, #023E8A, #0081AB); }
    .dsh-kpi-card.amber::before  { background: linear-gradient(90deg, #D97706, #F59E0B); }
    .dsh-kpi-card.green::before  { background: linear-gradient(90deg, #059669, #10B981); }
    .dsh-kpi-card.purple::before { background: linear-gradient(90deg, #7C3AED, #8B5CF6); }

    .dsh-kpi-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .dsh-kpi-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748B;
        margin: 0;
    }
    .dsh-kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dsh-kpi-icon svg {
        width: 20px;
        height: 20px;
    }
    .dsh-kpi-icon.blue   { background: rgba(0, 129, 171, 0.1); color: #0081AB; }
    .dsh-kpi-icon.amber  { background: rgba(217, 119, 6, 0.1); color: #D97706; }
    .dsh-kpi-icon.green  { background: rgba(5, 150, 105, 0.1); color: #059669; }
    .dsh-kpi-icon.purple { background: rgba(124, 58, 237, 0.1); color: #7C3AED; }

    .dsh-kpi-value-wrap {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-bottom: 10px;
    }
    .dsh-kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #1B2559;
        letter-spacing: -0.02em;
        line-height: 1;
        margin: 0;
    }
    .dsh-kpi-unit {
        font-size: 13px;
        font-weight: 600;
        color: #64748B;
    }

    .dsh-kpi-bottom {
        font-size: 12px;
        line-height: 1.4;
    }
    .dsh-kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .dsh-kpi-badge.green  { background: rgba(46, 158, 91, 0.1); color: #15803D; }
    .dsh-kpi-badge.amber  { background: rgba(217, 119, 6, 0.1); color: #B45309; }
    .dsh-kpi-badge.neutral{ background: #F1F5F9; color: #475569; }

    /* Mini Progress Bar for Target */
    .dsh-mini-progress {
        margin-top: 6px;
    }
    .dsh-mini-track {
        height: 6px;
        background: #F1F5F9;
        border-radius: 999px;
        overflow: hidden;
    }
    .dsh-mini-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #023E8A, #0081AB);
    }

    /* ===== Section Surface Cards ===== */
    .surface-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 8px 18px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #F1F5F9;
        flex-wrap: wrap;
        gap: 12px;
        background: #FFFFFF;
    }
    .section-header-bar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .section-header-bar-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #023E8A, #0081AB);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(2, 62, 138, 0.18);
    }
    .section-header-bar-icon svg {
        width: 18px;
        height: 18px;
        color: #FFFFFF;
    }
    .section-header-bar h2 {
        font-size: 16px;
        font-weight: 800;
        color: #1B2559;
        letter-spacing: -0.015em;
        margin: 0;
    }
    .section-header-bar p {
        font-size: 12.5px;
        color: #64748B;
        margin: 2px 0 0 0;
    }
    .link-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 700;
        color: #0081AB;
        background: rgba(0, 129, 171, 0.08);
        padding: 7px 14px;
        border-radius: 8px;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .link-btn:hover {
        background: #0081AB;
        color: #FFFFFF;
    }

    /* ===== Pengajuan Terbaru List ===== */
    .dsh-pengajuan-list {
        padding: 4px 0;
    }
    .dsh-pengajuan-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 22px;
        border-bottom: 1px solid #F8FAFC;
        transition: background 0.15s ease;
    }
    .dsh-pengajuan-item:hover {
        background: #F8FAFC;
    }
    .dsh-pengajuan-item:last-child {
        border-bottom: none;
    }
    .dsh-pengajuan-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(0, 129, 171, 0.08);
        color: #0081AB;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dsh-pengajuan-icon svg {
        width: 17px;
        height: 17px;
    }
    .dsh-pengajuan-info {
        flex: 1;
        min-width: 0;
    }
    .dsh-pengajuan-nama {
        font-weight: 700;
        font-size: 13.5px;
        color: #0F172A;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dsh-pengajuan-meta {
        font-size: 12px;
        color: #64748B;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .dsh-meta-chip {
        background: #F1F5F9;
        color: #334155;
        padding: 1px 7px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
    }
    .dsh-pengajuan-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .dsh-pengajuan-badge-selesai {
        background: rgba(46, 158, 91, 0.12);
        color: #15803D;
        border: 1px solid rgba(46, 158, 91, 0.25);
    }
    .dsh-pengajuan-badge-progress {
        background: rgba(232, 163, 23, 0.12);
        color: #B45309;
        border: 1px solid rgba(232, 163, 23, 0.25);
    }
    .dsh-pengajuan-badge-belum {
        background: #F1F5F9;
        color: #64748B;
        border: 1px solid #E2E8F0;
    }

    /* ===== Info Grid: Keuangan & Rekomendasi Lokasi ===== */
    .dsh-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) {
        .dsh-info-grid { grid-template-columns: 1fr; }
    }
    .dsh-info-grid > .surface-card {
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
    }

    .dsh-keuangan-body {
        padding: 22px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        flex: 1;
    }
    @media (max-width: 500px) {
        .dsh-keuangan-body { grid-template-columns: 1fr; }
    }
    .dsh-metric-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .dsh-metric-box-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748B;
        margin: 0 0 8px 0;
    }
    .dsh-metric-box-val {
        font-size: 24px;
        font-weight: 800;
        color: #1B2559;
        letter-spacing: -0.02em;
        margin: 0 0 6px 0;
        line-height: 1.1;
    }

    .dsh-zona-body {
        padding: 22px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .dsh-zona-bar {
        display: flex;
        height: 12px;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 14px;
        background: #F1F5F9;
        gap: 2px;
    }
    .dsh-zona-legend {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #475569;
        margin-bottom: 18px;
    }
    .dsh-zona-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }
    .dsh-zona-legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }
    .dsh-zona-callout {
        background: #F0F9FF;
        border: 1px solid #BAE6FD;
        border-left: 4px solid #0081AB;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 12.8px;
        color: #0C4A6E;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .dsh-zona-callout svg {
        width: 22px;
        height: 22px;
        color: #0081AB;
        flex-shrink: 0;
    }
    .dsh-zona-callout strong {
        color: #023E8A;
        font-weight: 800;
    }

    /* ===== Tren Transaksi with Integrated Filter ===== */
    .dsh-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding: 18px 22px;
        border-bottom: 1px solid #F1F5F9;
    }
    .dsh-chart-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .dsh-month-picker {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        padding: 4px 12px;
        transition: border-color 0.15s ease, background 0.15s ease;
    }
    .dsh-month-picker:focus-within {
        border-color: #0081AB;
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(0, 129, 171, 0.12);
    }
    .dsh-month-picker svg {
        width: 14px;
        height: 14px;
        color: #64748B;
        flex-shrink: 0;
    }
    .dsh-month-picker input {
        border: none;
        background: transparent;
        padding: 4px 0;
        font-size: 12.5px;
        font-weight: 600;
        color: #0F172A;
        width: 122px;
        font-family: inherit;
    }
    .dsh-month-picker input:focus {
        outline: none;
    }
    .dsh-month-picker-sep {
        color: #94A3B8;
        font-size: 12px;
    }

    /* ===== Grid 2-Col: Kalender & Jadwal Terdekat ===== */
    .dsh-grid-2col {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
        align-items: stretch;
    }
    @media (max-width: 900px) {
        .dsh-grid-2col { grid-template-columns: 1fr; }
    }
    .dsh-grid-2col > .surface-card {
        display: flex;
        flex-direction: column;
        height: 400px;
        margin-bottom: 0;
    }

    .calendar-widget {
        padding: 16px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }
    .calendar-day-label {
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        padding: 4px 0 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .calendar-cell {
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        position: relative;
        color: #1E293B;
        transition: background 0.15s ease, transform 0.15s ease;
    }
    .calendar-cell:not(.today):not(:empty):hover {
        background: #F1F5F9;
        cursor: default;
    }
    .calendar-cell.today {
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #FFFFFF;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(2, 62, 138, 0.28);
    }
    .calendar-cell.has-event::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #0081AB;
    }
    .calendar-cell.today.has-event::after {
        background: #FFC629;
    }

    .jadwal-widget {
        padding: 16px 20px;
        flex: 1;
        overflow-y: auto;
    }
    .jadwal-widget::-webkit-scrollbar { width: 5px; }
    .jadwal-widget::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .jadwal-section-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94A3B8;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .jadwal-section-label:not(:first-child) {
        margin-top: 18px;
    }
    .jadwal-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #E2E8F0;
    }
    .jadwal-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #F8FAFC;
        border-left: 3px solid #059669;
        margin-bottom: 8px;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .jadwal-item:hover {
        transform: translateX(3px);
        background: #F1F5F9;
    }
    .jadwal-item.besok {
        border-left-color: #D97706;
    }
    .jadwal-time {
        font-weight: 800;
        color: #023E8A;
        font-size: 13px;
        width: 44px;
        flex-shrink: 0;
    }
    .jadwal-info {
        flex: 1;
        min-width: 0;
    }
    .jadwal-title {
        font-weight: 700;
        font-size: 13px;
        color: #0F172A;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .jadwal-desc {
        font-size: 11.5px;
        color: #64748B;
        margin: 1px 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .jadwal-badge {
        display: inline-flex;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .jadwal-badge-online {
        background: rgba(46, 158, 91, 0.12);
        color: #15803D;
        border: 1px solid rgba(46, 158, 91, 0.2);
    }
    .jadwal-badge-offline {
        background: #E2E8F0;
        color: #475569;
    }

    /* ===== Top 5 Kandidat Prioritas Table ===== */
    .dsh-table {
        width: 100%;
        border-collapse: collapse;
    }
    .dsh-table thead th {
        background: #F8FAFC;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748B;
        padding: 14px 22px;
        border-bottom: 1px solid #E2E8F0;
    }
    .dsh-table td {
        padding: 14px 22px;
        font-size: 13.5px;
        border-bottom: 1px solid #F1F5F9;
        color: #1E293B;
    }
    .dsh-table tbody tr:hover {
        background: rgba(0, 129, 171, 0.04);
    }
    .dsh-table tbody tr:last-child td {
        border-bottom: none;
    }

    .dsh-rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
    }
    .dsh-rank-1 { background: #FEF3C7; color: #B45309; border: 1.5px solid #FCD34D; }
    .dsh-rank-2 { background: #F1F5F9; color: #475569; border: 1.5px solid #CBD5E1; }
    .dsh-rank-3 { background: #FFEDD5; color: #C2410C; border: 1.5px solid #FDBA74; }
    .dsh-rank-other { background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; }

    .badge-potensi-sangat-tinggi {
        background: rgba(46, 158, 91, 0.12);
        color: #15803D;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        border: 1px solid rgba(46, 158, 91, 0.25);
    }
    .badge-potensi-tinggi {
        background: rgba(232, 163, 23, 0.12);
        color: #B45309;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        border: 1px solid rgba(232, 163, 23, 0.25);
    }
    .badge-potensi-sedang {
        background: #F1F5F9;
        color: #475569;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        border: 1px solid #E2E8F0;
    }

    .progress-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .progress-bar-track {
        flex: 1;
        height: 7px;
        background: #F1F5F9;
        border-radius: 999px;
        overflow: hidden;
        min-width: 70px;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #10B981, #059669);
    }
    .progress-bar-fill.amber {
        background: linear-gradient(90deg, #F59E0B, #D97706);
    }
    .progress-bar-fill.red {
        background: linear-gradient(90deg, #EF4444, #DC2626);
    }
    .progress-bar-value {
        font-weight: 800;
        font-size: 13.5px;
        color: #0F172A;
        width: 34px;
        text-align: right;
    }
</style>

{{-- =========================================================
      1. TOPBAR & WELCOME BANNER
    ========================================================= --}}
<div class="dsh-topbar">
    <div>
        <h1 class="dsh-welcome-title">
            <svg style="width:26px; height:26px; color:#023E8A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>
            Dashboard Eksekutif SPKLU
        </h1>
        <p class="dsh-welcome-subtitle">Overview performa operasional, penetrasi wilayah, transaksi energi, dan pipeline kandidat SPKLU PLN UID</p>
    </div>

    <div class="dsh-topbar-actions">
        <div class="dsh-status-pill">
            <span class="dsh-pulse-dot"></span>
            Sistem Aktif
        </div>
        <div class="dsh-date-chip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</div>

{{-- =========================================================
      2. 4 KARTU KPI UTAMA
    ========================================================= --}}
<div class="dsh-card-grid">
    {{-- Card 1: Total SPKLU --}}
    <div class="dsh-card blue dsh-kpi-card">
        <div>
            <div class="dsh-kpi-top">
                <p class="dsh-kpi-label">Total SPKLU Terpasang</p>
                <div class="dsh-kpi-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
            </div>
            <div class="dsh-kpi-value-wrap">
                <p class="dsh-kpi-value">{{ number_format($totalSpkluTerpasang) }}</p>
                <span class="dsh-kpi-unit">unit aktif</span>
            </div>
        </div>
        <div class="dsh-kpi-bottom">
            @if (!empty($targetTahunan))
                @php $persenTarget = min(100, round(($totalSpkluTerpasang / $targetTahunan) * 100)); @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:11.5px; font-weight:600; color:#475569; margin-bottom:4px;">
                    <span>Target {{ now()->year }}: {{ $targetTahunan }} unit</span>
                    <span style="color:#0081AB; font-weight:800;">{{ $persenTarget }}%</span>
                </div>
                <div class="dsh-mini-track">
                    <div class="dsh-mini-fill" style="width: {{ $persenTarget }}%;"></div>
                </div>
            @else
                <span class="dsh-kpi-badge green">
                    <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                    +{{ $spkluBaruBulanIni ?? 0 }} unit baru bulan ini
                </span>
            @endif
        </div>
    </div>

    {{-- Card 2: Pengajuan On-Progress --}}
    <div class="dsh-card amber dsh-kpi-card">
        <div>
            <div class="dsh-kpi-top">
                <p class="dsh-kpi-label">Pengajuan On-Progress</p>
                <div class="dsh-kpi-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
            <div class="dsh-kpi-value-wrap">
                <p class="dsh-kpi-value">{{ number_format($pengajuanOnProgress) }}</p>
                <span class="dsh-kpi-unit">kandidat</span>
            </div>
        </div>
        <div class="dsh-kpi-bottom">
            <span class="dsh-kpi-badge amber">
                <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Dalam proses evaluasi & survei
            </span>
        </div>
    </div>

    {{-- Card 3: Kandidat Aktif --}}
    <div class="dsh-card green dsh-kpi-card">
        <div>
            <div class="dsh-kpi-top">
                <p class="dsh-kpi-label">Kandidat Aktif</p>
                <div class="dsh-kpi-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
            </div>
            <div class="dsh-kpi-value-wrap">
                <p class="dsh-kpi-value">{{ number_format($kandidatAktif) }}</p>
                <span class="dsh-kpi-unit">lokasi</span>
            </div>
        </div>
        <div class="dsh-kpi-bottom">
            @if ($kandidatButuhTindakLanjut > 0)
                <span class="dsh-kpi-badge amber">
                    <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $kandidatButuhTindakLanjut }} butuh tindak lanjut
                </span>
            @else
                <span class="dsh-kpi-badge green">
                    <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Semua kandidat berjalan lancar
                </span>
            @endif
        </div>
    </div>

    {{-- Card 4: Jadwal Mendatang --}}
    <div class="dsh-card purple dsh-kpi-card">
        <div>
            <div class="dsh-kpi-top">
                <p class="dsh-kpi-label">Jadwal Mendatang</p>
                <div class="dsh-kpi-icon purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>
            <div class="dsh-kpi-value-wrap">
                <p class="dsh-kpi-value">{{ ($jadwalHariIni->count() + $jadwalBesok->count()) }}</p>
                <span class="dsh-kpi-unit">agenda</span>
            </div>
        </div>
        <div class="dsh-kpi-bottom">
            <span class="dsh-kpi-badge neutral">
                <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Terjadwal hari ini &amp; besok
            </span>
        </div>
    </div>
</div>

{{-- =========================================================
      3. PENGAJUAN TERBARU (ACTIONABLE PIPELINE)
    ========================================================= --}}
<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <div>
                <h2>Pengajuan Kandidat Terbaru</h2>
                <p>5 kandidat lokasi SPKLU terkini yang sedang dalam tahap evaluasi</p>
            </div>
        </div>
        <a href="{{ route('monitoring.probabilitas.index') }}" class="link-btn">
            Lihat Semua Monitoring
            <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div class="dsh-pengajuan-list">
        @forelse ($pengajuanTerbaru as $p)
            @php
                $badgeClass = match ($p->status_kanban) {
                    'selesai_integrasi' => 'dsh-pengajuan-badge-selesai',
                    'on_progress' => 'dsh-pengajuan-badge-progress',
                    default => 'dsh-pengajuan-badge-belum',
                };
                $badgeLabel = match ($p->status_kanban) {
                    'selesai_integrasi' => 'Selesai Integrasi',
                    'on_progress' => 'On Progress',
                    default => 'Belum Mulai',
                };
            @endphp
            <div class="dsh-pengajuan-item">
                <div class="dsh-pengajuan-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
                <div class="dsh-pengajuan-info">
                    <p class="dsh-pengajuan-nama">{{ $p->lokasi }}</p>
                    <p class="dsh-pengajuan-meta">
                        <span class="dsh-meta-chip">{{ $p->ulp ?? 'ULP belum ditentukan' }}</span>
                        <span>&bull;</span>
                        <span>Tahap: <strong>{{ $p->tahap_saat_ini }}</strong></span>
                        <span>&bull;</span>
                        <span style="color:#94A3B8;">{{ $p->diajukan_pada->diffForHumans() }}</span>
                    </p>
                </div>
                <span class="dsh-pengajuan-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        @empty
            <div style="text-align:center; padding:36px 20px; color:#94A3B8; font-size:13px;">Belum ada pengajuan kandidat baru.</div>
        @endforelse
    </div>
</div>

{{-- =========================================================
      4. KEUANGAN & ENERGI + STATUS ZONA KANIBALISASI
    ========================================================= --}}
<div class="dsh-info-grid">
    {{-- Card: Keuangan & Energi --}}
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div>
                    <h2>Keuangan &amp; Konsumsi Energi</h2>
                    <p>Periode: <strong>{{ $ringkasanKeuangan['nama_bulan'] }}</strong></p>
                </div>
            </div>
        </div>
        <div class="dsh-keuangan-body">
            <div class="dsh-metric-box">
                <div>
                    <p class="dsh-metric-box-label">Pendapatan Bulan Ini</p>
                    <p class="dsh-metric-box-val">Rp {{ number_format($ringkasanKeuangan['pendapatan_bulan_ini'] / 1000000, 2) }} <span style="font-size:14px; font-weight:700; color:#64748B;">M</span></p>
                </div>
                <div>
                    <span class="dsh-kpi-badge {{ $ringkasanKeuangan['tren_pendapatan_persen'] >= 0 ? 'green' : 'amber' }}">
                        <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            @if ($ringkasanKeuangan['tren_pendapatan_persen'] >= 0)
                                <polyline points="18 15 12 9 6 15"/>
                            @else
                                <polyline points="6 9 12 15 18 9"/>
                            @endif
                        </svg>
                        {{ abs($ringkasanKeuangan['tren_pendapatan_persen']) }}% vs bulan lalu
                    </span>
                </div>
            </div>

            <div class="dsh-metric-box">
                <div>
                    <p class="dsh-metric-box-label">Energi Tersalurkan</p>
                    <p class="dsh-metric-box-val">{{ number_format($ringkasanKeuangan['energi_bulan_ini'] / 1000, 1) }} <span style="font-size:14px; font-weight:700; color:#64748B;">k kWh</span></p>
                </div>
                <div>
                    <span class="dsh-kpi-badge {{ $ringkasanKeuangan['tren_energi_persen'] >= 0 ? 'green' : 'amber' }}">
                        <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            @if ($ringkasanKeuangan['tren_energi_persen'] >= 0)
                                <polyline points="18 15 12 9 6 15"/>
                            @else
                                <polyline points="6 9 12 15 18 9"/>
                            @endif
                        </svg>
                        {{ abs($ringkasanKeuangan['tren_energi_persen']) }}% vs bulan lalu
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Rekomendasi Lokasi & Zona Kanibalisasi --}}
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                    </svg>
                </div>
                <div>
                    <h2>Status Zona Kanibalisasi SPKLU</h2>
                    <p>Pemantauan beban okupansi unit existing</p>
                </div>
            </div>
            <a href="{{ route('rekomendasi-lokasi.index') }}" class="link-btn">
                Buka Peta
                <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
        <div class="dsh-zona-body">
            @php $totalZona = max($ringkasanZona['total_spklu'], 1); @endphp
            <div>
                <div class="dsh-zona-bar">
                    <div style="width:{{ ($ringkasanZona['hijau'] / $totalZona) * 100 }}%; background:#2E9E5B; border-radius:999px 0 0 999px;" title="Aman"></div>
                    <div style="width:{{ ($ringkasanZona['kuning'] / $totalZona) * 100 }}%; background:#E8A317;" title="Waspada"></div>
                    <div style="width:{{ ($ringkasanZona['merah'] / $totalZona) * 100 }}%; background:#C0392B;" title="Padat"></div>
                    <div style="width:{{ ($ringkasanZona['belum_ada_data'] / $totalZona) * 100 }}%; background:#CBD5E1; border-radius:0 999px 999px 0;" title="Belum Ada Data"></div>
                </div>
                <div class="dsh-zona-legend">
                    <span class="dsh-zona-legend-item"><span class="dsh-zona-legend-dot" style="background:#2E9E5B;"></span> {{ $ringkasanZona['hijau'] }} Aman</span>
                    <span class="dsh-zona-legend-item"><span class="dsh-zona-legend-dot" style="background:#E8A317;"></span> {{ $ringkasanZona['kuning'] }} Waspada</span>
                    <span class="dsh-zona-legend-item"><span class="dsh-zona-legend-dot" style="background:#C0392B;"></span> {{ $ringkasanZona['merah'] }} Padat</span>
                </div>
            </div>
            <div class="dsh-zona-callout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <div>
                    @if ($wilayahPotensialTop)
                        Wilayah paling potensial untuk ekspansi: <strong>{{ $wilayahPotensialTop['ulp'] }}</strong> ({{ $wilayahPotensialTop['persen_hijau'] }}% zona hijau aman).
                    @else
                        Belum cukup data transaksi untuk kalkulasi rekomendasi wilayah ekspansi.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
      5. TREN TRANSAKSI DENGAN INTEGRATED FILTER BULAN
    ========================================================= --}}
<div class="surface-card">
    <div class="dsh-chart-header">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <div>
                <h2 style="color:#1B2559;">Tren Transaksi SPKLU</h2>
                <p>Volume transaksi kumulatif: {{ \Carbon\Carbon::createFromFormat('Y-m', $dariBulan)->locale('id')->translatedFormat('F Y') }} &ndash; {{ \Carbon\Carbon::createFromFormat('Y-m', $sampaiBulan)->locale('id')->translatedFormat('F Y') }}</p>
            </div>
        </div>

        <div class="dsh-chart-toolbar">
            <span class="dsh-kpi-badge {{ $trenTransaksiPersen >= 0 ? 'green' : 'amber' }}">
                <svg style="width:12px; height:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    @if ($trenTransaksiPersen >= 0)
                        <polyline points="18 15 12 9 6 15"/>
                    @else
                        <polyline points="6 9 12 15 18 9"/>
                    @endif
                </svg>
                {{ abs($trenTransaksiPersen) }}% vs periode lalu
            </span>

            {{-- Integrated Month Picker Form --}}
            <form method="GET" id="form-filter-dashboard" style="margin:0;">
                <div class="dsh-month-picker">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <input type="month" name="dari_bulan" value="{{ $dariBulan }}" onchange="document.getElementById('form-filter-dashboard').submit()" title="Bulan Awal">
                    <span class="dsh-month-picker-sep">&mdash;</span>
                    <input type="month" name="sampai_bulan" value="{{ $sampaiBulan }}" onchange="document.getElementById('form-filter-dashboard').submit()" title="Bulan Akhir">
                </div>
            </form>
        </div>
    </div>
    <div style="padding: 24px 26px;">
        <canvas id="chart-tren-dashboard" height="75"></canvas>
    </div>
</div>

{{-- =========================================================
      6. KALENDER & JADWAL TERDEKAT (2-COLUMN GRID)
    ========================================================= --}}
<div class="dsh-grid-2col">
    {{-- Kalender --}}
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <h2>Kalender Aktivitas</h2>
                    <p>{{ now()->translatedFormat('F Y') }}</p>
                </div>
            </div>
            <a href="{{ route('penjadwalan.index') }}" class="link-btn">Kelola Jadwal</a>
        </div>

        <div class="calendar-widget">
            @php
                $bulanIni = now();
                $awalBulan = $bulanIni->copy()->startOfMonth();
                $akhirBulan = $bulanIni->copy()->endOfMonth();
                $offsetAwal = $awalBulan->dayOfWeekIso - 1;
            @endphp

            <div class="calendar-grid">
                @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $hari)
                    <div class="calendar-day-label">{{ $hari }}</div>
                @endforeach

                @for ($i = 0; $i < $offsetAwal; $i++)
                    <div></div>
                @endfor

                @for ($tgl = 1; $tgl <= $akhirBulan->day; $tgl++)
                    @php
                        $isToday = $tgl === $bulanIni->day;
                        $hasEvent = in_array((string) $tgl, $kalenderBulanIni['tanggalBerjadwal'] ?? []);
                    @endphp
                    <div class="calendar-cell {{ $isToday ? 'today' : '' }} {{ $hasEvent ? 'has-event' : '' }}">{{ $tgl }}</div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Jadwal Terdekat --}}
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <h2>Agenda &amp; Jadwal Terdekat</h2>
                    <p>Pertemuan &amp; survei lokasi terdekat</p>
                </div>
            </div>
        </div>

        <div class="jadwal-widget">
            <p class="jadwal-section-label">Hari Ini &bull; {{ now()->translatedFormat('d M') }}</p>
            @forelse ($jadwalHariIni as $jadwal)
                <div class="jadwal-item">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ \Illuminate\Support\Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="jadwal-badge {{ $jadwal->mode === 'online' ? 'jadwal-badge-online' : 'jadwal-badge-offline' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p style="text-align:center; padding:12px 10px; color:#94A3B8; font-size:12.5px; margin:0;">Tidak ada jadwal agenda untuk hari ini.</p>
            @endforelse

            <p class="jadwal-section-label">Besok &bull; {{ now()->addDay()->translatedFormat('d M') }}</p>
            @forelse ($jadwalBesok as $jadwal)
                <div class="jadwal-item besok">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ \Illuminate\Support\Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="jadwal-badge {{ $jadwal->mode === 'online' ? 'jadwal-badge-online' : 'jadwal-badge-offline' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p style="text-align:center; padding:12px 10px; color:#94A3B8; font-size:12.5px; margin:0;">Tidak ada agenda untuk besok.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- =========================================================
      7. TOP 5 KANDIDAT PRIORITAS (RANKED TABLE)
    ========================================================= --}}
<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
            <div>
                <h2>Top 5 Kandidat Prioritas SPKLU</h2>
                <p>Peringkat kelayakan lokasi berdasarkan evaluasi multi-kriteria (MCDM Progres, Kapasitas Jaringan, Demand, &amp; Okupansi)</p>
            </div>
        </div>
        <a href="{{ route('kandidat-peringkat.index') }}" class="link-btn">
            Lihat Peringkat Lengkap
            <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="dsh-table">
            <thead>
                <tr>
                    <th style="width:8%; text-align:center;">Peringkat</th>
                    <th style="width:36%;">Nama Lokasi Kandidat</th>
                    <th style="width:24%;">Wilayah / ULP</th>
                    <th style="width:16%;">Tingkat Potensi</th>
                    <th style="width:16%;">Skor Kelayakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topKandidat as $i => $kandidat)
                    @php
                        $rankClass = match($i) {
                            0 => 'dsh-rank-1',
                            1 => 'dsh-rank-2',
                            2 => 'dsh-rank-3',
                            default => 'dsh-rank-other',
                        };
                        $potensiClass = match(true) {
                            $kandidat->skor >= 85 => 'badge-potensi-sangat-tinggi',
                            $kandidat->skor >= 65 => 'badge-potensi-tinggi',
                            default => 'badge-potensi-sedang',
                        };
                        $potensiLabel = match(true) {
                            $kandidat->skor >= 85 => 'Sangat Tinggi',
                            $kandidat->skor >= 65 => 'Tinggi',
                            default => 'Sedang',
                        };
                        $barClass = $kandidat->skor >= 75 ? '' : ($kandidat->skor >= 50 ? 'amber' : 'red');
                    @endphp
                    <tr>
                        <td style="text-align:center;">
                            <span class="dsh-rank-badge {{ $rankClass }}">{{ $i + 1 }}</span>
                        </td>
                        <td>
                            <strong style="color:#0F172A; font-size:14px;">{{ $kandidat->nama }}</strong>
                        </td>
                        <td>
                            <span style="display:inline-flex; align-items:center; gap:5px; color:#475569;">
                                <svg style="width:13px; height:13px; color:#0081AB;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $kandidat->wilayah }}
                            </span>
                        </td>
                        <td><span class="{{ $potensiClass }}">{{ $potensiLabel }}</span></td>
                        <td>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-track">
                                    <div class="progress-bar-fill {{ $barClass }}" style="width:{{ min(100, $kandidat->skor) }}%;"></div>
                                </div>
                                <span class="progress-bar-value">{{ $kandidat->skor }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; padding:36px 20px; color:#94A3B8;">Belum ada data kandidat prioritas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- =========================================================
      CHART.JS SCRIPTS
    ========================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    Chart.register(ChartDataLabels);

    const ctx = document.getElementById('chart-tren-dashboard').getContext('2d');
    
    // Create soft gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 129, 171, 0.22)');
    gradient.addColorStop(1, 'rgba(0, 129, 171, 0.00)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenTransaksiLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($trenTransaksiData ?? []) !!},
                borderColor: '#0081AB',
                backgroundColor: gradient,
                tension: 0.38,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#023E8A',
                pointBorderWidth: 2.5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#0081AB',
                pointHoverBorderColor: '#FFFFFF',
                pointHoverBorderWidth: 2,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Inter', size: 12, weight: '700' },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Transaksi: ' + new Intl.NumberFormat('id-ID').format(context.parsed.y) + ' sesi';
                        }
                    }
                },
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    color: '#023E8A',
                    font: { weight: '800', size: 11, family: 'Inter' },
                    formatter: (value) => new Intl.NumberFormat('id-ID').format(value),
                    offset: 4,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: { family: 'Inter', size: 11, weight: '600' },
                        color: '#64748B',
                    }
                },
                y: {
                    beginAtZero: true,
                    grace: '15%',
                    grid: {
                        color: '#F1F5F9',
                    },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94A3B8',
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            layout: { padding: { top: 24, right: 12, left: 6, bottom: 4 } }
        }
    });
</script>

@endsection