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
        padding-bottom: 16px;
        border-bottom: 1px solid #E2E8F0;
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
        background: #023E8A;
        color: #fff;
        box-shadow: 0 2px 6px rgba(2,62,138,.2);
    }
    .mu-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2,62,138,.3);
        background: #002D66;
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

    /* Header Bar Putih Bersih */
    .mu-section-header {
        background: #FFFFFF !important;
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
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .mu-section-header-icon svg {
        width: 17px;
        height: 17px;
        stroke-width: 2;
        color: #fff;
    }
    .mu-section-header-title {
        font-size: 16.5px;
        font-weight: 800;
        color: #1B2559;
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
        background: #EFF6FB;
        padding: 6px 14px;
        border-radius: 999px;
        border: 1px solid #BAE6FD;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mu-count-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #0081AB;
    }

    /* ── Filter & Search Toolbar (SATU BARIS SEJAJAR) ── */
    .mu-toolbar {
        padding: 14px 22px;
        background: #FAFBFD;
        border-bottom: 1px solid #EEF2F6;
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
        left: 14px;
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
        height: 40px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-right: 14px !important;
        padding-left: 40px !important;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        background: #ffffff;
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
        height: 40px;
        padding: 0 36px 0 14px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        background: #ffffff;
        color: #334155;
        font-weight: 500;
        cursor: pointer;
        transition: border-color .15s ease, box-shadow .15s ease;
        flex: 0 0 170px;
        width: 170px;
        box-sizing: border-box;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
    }
    .mu-select-filter:hover {
        border-color: #cbd5e1;
    }
    .mu-select-filter:focus {
        outline: none;
        border-color: #0081AB;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,129,171,.12);
    }
    .mu-reset-btn {
        height: 40px;
        font-size: 12.5px;
        color: #C0392B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 14px;
        border-radius: 10px;
        background: rgba(192,57,43,.08);
        border: 1px solid rgba(192,57,43,.18);
        font-weight: 700;
        flex-shrink: 0;
        white-space: nowrap;
        transition: all .15s ease;
        box-sizing: border-box;
    }
    .mu-reset-btn:hover {
        background: rgba(192,57,43,.16);
        border-color: rgba(192,57,43,.3);
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
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748B;
        padding: 14px 22px;
        border-bottom: 1px solid #EEF1F5;
        white-space: nowrap;
    }
    .mu-table tbody td {
        padding: 16px 22px;
        font-size: 13.5px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #1E293B;
    }
    .mu-table tbody tr {
        transition: background .12s ease;
    }
    .mu-table tbody tr:hover {
        background: rgba(0, 129, 171, 0.025);
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
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        letter-spacing: -0.02em;
        transition: transform .15s ease;
    }
    .mu-avatar.role-super_admin {
        background: #EBF3FA;
        color: #023E8A;
        border: 1px solid #B8D5ED;
    }
    .mu-avatar.role-pemasaran {
        background: #F0F9FF;
        color: #0081AB;
        border: 1px solid #BAE6FD;
    }
    .mu-avatar.role-pengelola {
        background: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
    }
    .mu-avatar.role-manajemen {
        background: #F5F3FF;
        color: #7C3AED;
        border: 1px solid #DDD6FE;
    }
    .mu-user-name {
        font-weight: 750;
        color: #1B2559;
        margin: 0;
        font-size: 13.8px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .mu-user-you {
        font-size: 9.5px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 5px;
        background: rgba(2, 62, 138, 0.08);
        color: #023E8A;
        border: 1px solid rgba(2, 62, 138, 0.16);
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .mu-user-sub {
        font-size: 12px;
        color: #94A3B8;
        margin: 3px 0 0;
        font-weight: 500;
    }

    /* ── Role Badges ── */
    .mu-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4.5px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }
    .mu-role-super_admin {
        background: #EBF3FA;
        color: #023E8A;
        border: 1px solid #B8D5ED;
    }
    .mu-role-pemasaran {
        background: #F0F9FF;
        color: #0081AB;
        border: 1px solid #BAE6FD;
    }
    .mu-role-pengelola {
        background: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
    }
    .mu-role-manajemen {
        background: #F8FAFC;
        color: #475569;
        border: 1px solid #E2E8F0;
    }
    .mu-badge-up3 {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4.5px 11px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: #EFF6FB;
        color: #023E8A;
        border: 1px solid #BAE6FD;
        white-space: nowrap;
    }
    .mu-badge-up3 svg {
        color: #0081AB;
    }
    .mu-empty-dash {
        color: #CBD5E1;
        font-weight: 600;
        font-size: 14px;
    }

    /* ── Status Styles ── */
    .mu-status-cell {
        display: flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
    }
    .mu-pending-wrap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }
    .mu-badge-pending {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        background: #FEF3C7;
        color: #B45309;
        border: 1px solid #FDE68A;
        white-space: nowrap;
    }
    .mu-pending-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #D97706;
        flex-shrink: 0;
    }
    .mu-btn-resend {
        background: rgba(0, 129, 171, 0.08);
        border: 1px solid rgba(0, 129, 171, 0.2);
        color: #0081AB;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        padding: 3.5px 8px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        transition: all .15s ease;
    }
    .mu-btn-resend:hover {
        background: #0081AB;
        color: #ffffff;
        border-color: #0081AB;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 129, 171, 0.25);
    }
    .mu-btn-resend svg {
        width: 12px;
        height: 12px;
        stroke-width: 2.2;
    }

    /* Soft Toggle Switch */
    .mu-toggle {
        width: 38px;
        height: 22px;
        border-radius: 999px;
        background: #CBD5E1;
        border: none;
        position: relative;
        cursor: pointer;
        transition: background .2s ease;
        padding: 0;
        flex-shrink: 0;
    }
    .mu-toggle.on {
        background: #16A34A;
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
        box-shadow: 0 1px 3px rgba(0,0,0,.18);
        transition: left .2s ease;
    }
    .mu-toggle.on .mu-toggle-knob {
        left: 18px;
    }
    .mu-status-text {
        font-size: 12.5px;
        font-weight: 700;
    }
    .mu-status-text.active { color: #16A34A; }
    .mu-status-text.inactive { color: #94A3B8; }

    /* ── Action Buttons ── */
    .mu-actions {
        display: flex;
        gap: 7px;
        align-items: center;
        justify-content: flex-end;
    }
    .mu-icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid rgba(245, 158, 11, 0.25);
        background: rgba(245, 158, 11, 0.08);
        color: #D97706;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
        box-sizing: border-box;
    }
    .mu-icon-btn:hover {
        background: rgba(245, 158, 11, 0.18);
        border-color: rgba(245, 158, 11, 0.4);
        color: #B45309;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }
    .mu-icon-btn svg {
        width: 15px;
        height: 15px;
        stroke-width: 2.2;
    }

    .mu-view-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid rgba(0, 129, 171, 0.25);
        background: rgba(0, 129, 171, 0.08);
        color: #0081AB;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
        box-sizing: border-box;
    }
    .mu-view-btn:hover {
        background: rgba(0, 129, 171, 0.18);
        border-color: rgba(0, 129, 171, 0.4);
        color: #023E8A;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 129, 171, 0.2);
    }
    .mu-view-btn svg {
        width: 16px;
        height: 16px;
        stroke-width: 2;
    }

    .mu-del-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid rgba(239, 68, 68, 0.25);
        background: rgba(239, 68, 68, 0.08);
        color: #DC2626;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
        box-sizing: border-box;
    }
    .mu-del-btn:hover {
        background: rgba(239, 68, 68, 0.16);
        border-color: rgba(239, 68, 68, 0.4);
        color: #B91C1C;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);
    }
    .mu-del-btn svg {
        width: 14px;
        height: 14px;
        stroke-width: 2.2;
    }
    .mu-del-btn:disabled {
        opacity: .25;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
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
        background: #FFFFFF !important;
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
        padding: 22px 24px 24px;
    }
    .mu-field {
        margin-bottom: 16px;
    }
    .mu-field:last-of-type {
        margin-bottom: 0;
    }
    .mu-field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 7px;
        letter-spacing: .01em;
    }
    .mu-field input,
    .mu-field select {
        width: 100%;
        height: 42px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1.5px solid #E2E8F0;
        font-size: 13.5px;
        background: #fff;
        color: #0F172A;
        font-family: inherit;
        transition: border-color .15s ease, box-shadow .15s ease;
        box-sizing: border-box;
    }
    .mu-field select {
        padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
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
        background: #F0F9FF;
        border: 1px solid #BAE6FD;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 12px;
        color: #0369A1;
        margin-top: 14px;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 9px;
    }
    .mu-hint-box svg {
        width: 16px;
        height: 16px;
        color: #0284C7;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .mu-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        padding: 16px 24px;
        background: #FAFBFD;
        border-top: 1px solid #EEF2F6;
        margin: 22px -24px -24px -24px;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
    }
    .mu-modal-email-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
        font-size: 12px;
        font-weight: 600;
        color: #0284C7;
        background: #F0F9FF;
        border: 1px solid #BAE6FD;
        padding: 2px 8px;
        border-radius: 6px;
    }
    /* ── Modal Large (Detail User) ── */
    .mu-modal-lg {
        width: 660px !important;
        max-width: 95vw !important;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    .mu-modal-scrollable {
        overflow-y: auto;
        padding: 20px 24px;
        max-height: calc(90vh - 130px);
    }
    .mu-detail-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 20px;
    }
    .mu-detail-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
    }
    .mu-detail-avatar {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .mu-detail-meta {
        flex: 1;
        min-width: 0;
    }
    .mu-detail-name {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mu-detail-email {
        font-size: 13px;
        color: #64748B;
        margin: 2px 0 0;
    }
    .mu-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid #E2E8F0;
    }
    @media (max-width: 540px) {
        .mu-detail-grid {
            grid-template-columns: 1fr;
        }
    }
    .mu-detail-item {
        background: #fff;
        border: 1px solid #EDF2F7;
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .mu-detail-item-highlight {
        grid-column: span 2;
        background: linear-gradient(135deg, rgba(2,62,138,.04), rgba(0,129,171,.08));
        border: 1px solid #BAE6FD;
    }
    @media (max-width: 540px) {
        .mu-detail-item-highlight {
            grid-column: span 1;
        }
    }
    .mu-detail-item-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: .04em;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .mu-detail-item-val {
        font-size: 13px;
        font-weight: 750;
        color: #1E293B;
    }
    .mu-detail-item-val.highlight {
        color: #023E8A;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
    }

    /* Sub Tabs inside Modal Detail */
    .mu-subtabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        border-bottom: 1px solid #EEF2F6;
        padding-bottom: 10px;
    }
    .mu-subtab-btn {
        background: transparent;
        border: none;
        padding: 8px 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #64748B;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all .15s ease;
    }
    .mu-subtab-btn:hover {
        background: #F1F5F9;
        color: #1E293B;
    }
    .mu-subtab-btn.active {
        background: #EFF6FB;
        color: #0081AB;
    }
    .mu-subtab-badge {
        font-size: 11px;
        background: #E2E8F0;
        color: #475569;
        padding: 1px 7px;
        border-radius: 999px;
    }
    .mu-subtab-btn.active .mu-subtab-badge {
        background: #BAE6FD;
        color: #023E8A;
    }

    /* Timeline Activity & Logs */
    .mu-timeline {
        position: relative;
        padding-left: 28px;
    }
    .mu-timeline::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 11px;
        width: 2px;
        background: #E2E8F0;
    }
    .mu-timeline-item {
        position: relative;
        margin-bottom: 16px;
    }
    .mu-timeline-item:last-child {
        margin-bottom: 0;
    }
    .mu-timeline-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #CBD5E1;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        z-index: 2;
    }
    .mu-timeline-dot.login {
        border-color: #16A34A;
        background: #DCFCE7;
        color: #15803D;
    }
    .mu-timeline-dot.logout {
        border-color: #94A3B8;
        background: #F1F5F9;
        color: #64748B;
    }
    .mu-timeline-dot.change {
        border-color: #0081AB;
        background: #E0F2FE;
        color: #023E8A;
    }
    .mu-timeline-dot svg {
        width: 12px;
        height: 12px;
        stroke-width: 2.4;
    }
    .mu-timeline-card {
        background: #fff;
        border: 1px solid #EDF2F7;
        border-radius: 11px;
        padding: 12px 14px;
        box-shadow: 0 1px 3px rgba(15,23,42,.03);
        transition: border-color .15s ease;
    }
    .mu-timeline-card:hover {
        border-color: #CBD5E1;
    }
    .mu-timeline-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
        flex-wrap: wrap;
    }
    .mu-timeline-title {
        font-size: 13px;
        font-weight: 750;
        color: #1E293B;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mu-timeline-time {
        font-size: 11.5px;
        color: #94A3B8;
        font-weight: 500;
        white-space: nowrap;
    }
    .mu-timeline-sub {
        font-size: 12px;
        color: #64748B;
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .mu-timeline-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .mu-diff-box {
        margin-top: 8px;
        background: #F8FAFC;
        border: 1px dashed #CBD5E1;
        border-radius: 7px;
        padding: 8px 10px;
        font-size: 11.5px;
        color: #334155;
    }
    .mu-diff-item {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
    }
    .mu-diff-item:last-child {
        margin-bottom: 0;
    }
    .mu-diff-label {
        font-weight: 700;
        color: #64748B;
        min-width: 60px;
    }
    .mu-diff-old {
        text-decoration: line-through;
        color: #EF4444;
        background: #FEF2F2;
        padding: 1px 5px;
        border-radius: 4px;
    }
    .mu-diff-arrow {
        color: #94A3B8;
        font-size: 11px;
    }
    .mu-diff-new {
        color: #16A34A;
        font-weight: 700;
        background: #F0FDF4;
        padding: 1px 5px;
        border-radius: 4px;
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
            <span class="mu-count-dot"></span>
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

        <select name="up3" class="mu-select-filter" onchange="this.form.submit()">
            <option value="">Semua UP3</option>
            @foreach (\App\Models\User::DAFTAR_UP3 as $uUp3)
                <option value="{{ $uUp3 }}" {{ request('up3') === $uUp3 ? 'selected' : '' }}>{{ $uUp3 }}</option>
            @endforeach
        </select>

        <select name="status" class="mu-select-filter" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Aktivasi</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        @if (request()->filled('search') || request()->filled('role') || request()->filled('status') || request()->filled('up3'))
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
                    <th>UP3</th>
                    <th>Role / Akses</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                <tr>
                    <td>
                        <div class="mu-user-cell">
                            <div class="mu-avatar role-{{ $u->role }}">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="mu-user-name" onclick="openDetailUser({{ $u->id }})" style="cursor: pointer;" title="Lihat detail & riwayat pengguna">
                                    <span style="border-bottom: 1px dashed rgba(2,62,138,0.35);">{{ $u->name }}</span>
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
                        @if ($u->up3)
                            <span class="mu-badge-up3" title="Unit Pelaksana: {{ $u->up3 }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="12" height="12" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $u->up3 }}
                            </span>
                        @else
                            <span class="mu-empty-dash">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="mu-role-badge mu-role-{{ $u->role }}">
                            {{ ucwords(str_replace('_', ' ', $u->role)) }}
                        </span>
                    </td>
                    <td>
                        <div class="mu-status-cell">
                            @if ($u->status->value === 'pending')
                                <div class="mu-pending-wrap">
                                    <span class="mu-badge-pending">
                                        <span class="mu-pending-dot"></span>
                                        Menunggu Aktivasi
                                    </span>
                                    <form method="POST" action="{{ route('manajemen-user.resend-invitation', $u) }}" style="display:inline; margin:0;">
                                        @csrf
                                        <button type="submit" class="mu-btn-resend" title="Kirim ulang email undangan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                            Kirim Ulang
                                        </button>
                                    </form>
                                </div>
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
                        <div class="mu-actions" style="justify-content: flex-end;">
                            <button type="button" class="mu-view-btn" onclick="openDetailUser({{ $u->id }})" title="Lihat Detail & Riwayat Pengguna">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>

                            <button type="button" class="mu-icon-btn btn-edit-user" title="Edit Data Pengguna"
                                    data-id="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-email="{{ $u->email }}"
                                    data-role="{{ $u->role }}"
                                    data-up3="{{ $u->up3 }}">
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
                            @if (request()->filled('search') || request()->filled('role') || request()->filled('status') || request()->filled('up3'))
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

            <div class="mu-field">
                <label for="new-up3">Unit Kerja (UP3 Pelaksana)</label>
                <select id="new-up3" name="up3">
                    <option value="">-- Tidak Ditentukan / Kantor Pusat --</option>
                    @foreach (\App\Models\User::DAFTAR_UP3 as $optUp3)
                        <option value="{{ $optUp3 }}">{{ $optUp3 }}</option>
                    @endforeach
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
                <div class="mu-modal-header-icon" style="width: 36px; height: 36px; border-radius: 10px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #1B2559;">Edit Data Pengguna</h3>
                    <div id="edit-user-email-display" class="mu-modal-email-chip"></div>
                </div>
            </div>
            <button type="button" class="mu-modal-close" onclick="closeModal('modal-edit-user')" title="Tutup">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="edit-user-form" method="POST" action="" class="mu-modal-body">
            @csrf
            @method('PATCH')

            <div class="mu-field">
                <label for="edit-user-name">Nama Lengkap</label>
                <input type="text" id="edit-user-name" name="name" placeholder="Masukkan nama lengkap pengguna" required>
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

            <div class="mu-field">
                <label for="edit-user-up3">Unit Kerja (UP3 Pelaksana)</label>
                <select id="edit-user-up3" name="up3">
                    <option value="">-- Tidak Ditentukan / Kantor Pusat --</option>
                    @foreach (\App\Models\User::DAFTAR_UP3 as $optUp3)
                        <option value="{{ $optUp3 }}">{{ $optUp3 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mu-hint-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Perubahan data langsung tersimpan. Alamat email dilindungi dan tidak dapat diubah demi audit integritas.</span>
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

{{-- =========================================================
     MODAL DETAIL USER & RIWAYAT AKTIVITAS
========================================================= --}}
<div id="modal-detail-user" class="mu-modal-overlay" onclick="handleBackdropClick(event, 'modal-detail-user')">
    <div class="mu-modal mu-modal-lg" onclick="event.stopPropagation()">
        <div class="mu-modal-header">
            <div class="mu-modal-header-left">
                <div class="mu-modal-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div>
                    <h3>Detail Pengguna &amp; Riwayat Aktivitas</h3>
                    <p id="detail-user-subtitle" style="margin: 2px 0 0; font-size: 11.5px; color: #64748B;">Memuat data pengguna...</p>
                </div>
            </div>
            <button type="button" class="mu-modal-close" onclick="closeModal('modal-detail-user')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div id="modal-detail-body" class="mu-modal-scrollable">
            {{-- Loading Spinner --}}
            <div id="detail-loading" style="text-align: center; padding: 45px 20px;">
                <svg style="animation: spin 1s linear infinite; width: 32px; height: 32px; color: #0081AB; margin: 0 auto 12px; display: block;" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-opacity="0.25" stroke-width="3"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-width="3" stroke-linecap="round"></path></svg>
                <p style="font-size: 13.5px; color: #64748B; margin: 0;">Mengambil rincian akun &amp; riwayat aktivitas...</p>
            </div>

            {{-- Detail Content --}}
            <div id="detail-content" style="display: none;">
                {{-- Profile Info Card --}}
                <div class="mu-detail-card">
                    <div class="mu-detail-top">
                        <div id="detail-avatar" class="mu-detail-avatar"></div>
                        <div class="mu-detail-meta">
                            <h4 id="detail-name" class="mu-detail-name"></h4>
                            <p id="detail-email" class="mu-detail-email"></p>
                        </div>
                    </div>
                    <div class="mu-detail-grid">
                        <div class="mu-detail-item">
                            <span class="mu-detail-item-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Role / Hak Akses
                            </span>
                            <span id="detail-role" class="mu-detail-item-val"></span>
                        </div>
                        <div class="mu-detail-item">
                            <span class="mu-detail-item-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                Unit Pelaksana (UP3)
                            </span>
                            <span id="detail-up3" class="mu-detail-item-val"></span>
                        </div>
                        <div class="mu-detail-item">
                            <span class="mu-detail-item-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Status Akun
                            </span>
                            <span id="detail-status" class="mu-detail-item-val"></span>
                        </div>
                        <div class="mu-detail-item">
                            <span class="mu-detail-item-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                Terdaftar Sejak
                            </span>
                            <span id="detail-registered" class="mu-detail-item-val"></span>
                        </div>
                        {{-- HIGHLIGHT TERAKHIR LOGIN --}}
                        <div class="mu-detail-item mu-detail-item-highlight">
                            <span class="mu-detail-item-label" style="color: #0081AB;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Sesi Terakhir Login
                            </span>
                            <div id="detail-last-login" class="mu-detail-item-val highlight">
                                {{-- Rendered dynamically --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Activity Sub-Tabs --}}
                <div class="mu-subtabs">
                    <button type="button" class="mu-subtab-btn active" id="tab-btn-login" onclick="switchDetailSubTab('login')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Riwayat Login &amp; Logout
                        <span id="count-login-logs" class="mu-subtab-badge">0</span>
                    </button>
                    <button type="button" class="mu-subtab-btn" id="tab-btn-changes" onclick="switchDetailSubTab('changes')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                        Riwayat Perubahan Data
                        <span id="count-change-logs" class="mu-subtab-badge">0</span>
                    </button>
                </div>

                {{-- Tab Pane 1: Riwayat Login & Logout --}}
                <div id="pane-detail-login">
                    <div id="list-login-logs" class="mu-timeline"></div>
                    <div id="empty-login-logs" class="mu-empty-box" style="display:none; padding: 25px 15px;">
                        <div class="mu-empty-icon" style="width: 38px; height: 38px; margin-bottom: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <p class="mu-empty-title" style="font-size: 13.5px;">Belum Ada Riwayat Sesi</p>
                        <p class="mu-empty-text" style="font-size: 12px; margin: 0;">Pengguna ini belum memiliki catatan riwayat login atau logout di sistem.</p>
                    </div>
                </div>

                {{-- Tab Pane 2: Riwayat Perubahan Data --}}
                <div id="pane-detail-changes" style="display:none;">
                    <div id="list-change-logs" class="mu-timeline"></div>
                    <div id="empty-change-logs" class="mu-empty-box" style="display:none; padding: 25px 15px;">
                        <div class="mu-empty-icon" style="width: 38px; height: 38px; margin-bottom: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <p class="mu-empty-title" style="font-size: 13.5px;">Belum Ada Riwayat Perubahan</p>
                        <p class="mu-empty-text" style="font-size: 12px; margin: 0;">Belum ada perubahan data (audit log) yang tercatat untuk atau oleh pengguna ini.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mu-modal-footer" style="margin-top: 0; padding: 14px 22px; background: #FAFBFD; border-top: 1px solid #EEF2F6;">
            <button type="button" class="mu-btn mu-btn-outline" onclick="closeModal('modal-detail-user')">Tutup</button>
            <button type="button" class="mu-btn mu-btn-primary" id="btn-edit-from-detail" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                Edit Pengguna
            </button>
        </div>
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

function openDetailUser(userId) {
    openModal('modal-detail-user');

    const loadingEl = document.getElementById('detail-loading');
    const contentEl = document.getElementById('detail-content');
    const subtitleEl = document.getElementById('detail-user-subtitle');
    const editBtn = document.getElementById('btn-edit-from-detail');

    loadingEl.style.display = 'block';
    contentEl.style.display = 'none';
    subtitleEl.textContent = 'Memuat data pengguna...';
    editBtn.style.display = 'none';

    // Default to login tab
    switchDetailSubTab('login');

    fetch(`/manajemen-user/${userId}/detail`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal mengambil data detail');
        return res.json();
    })
    .then(data => {
        const u = data.user;

        subtitleEl.textContent = `${u.email} • ID #${u.id}`;

        // Avatar
        const avatarEl = document.getElementById('detail-avatar');
        avatarEl.className = `mu-detail-avatar role-${u.role}`;
        avatarEl.textContent = u.initials;

        // Name
        document.getElementById('detail-name').innerHTML = `
            ${escapeHtml(u.name)}
            ${u.is_current_user ? '<span class="mu-user-you">Anda</span>' : ''}
        `;
        document.getElementById('detail-email').textContent = u.email;

        // Role
        document.getElementById('detail-role').innerHTML = `
            <span class="mu-role-badge mu-role-${u.role}">${escapeHtml(u.role_label)}</span>
        `;

        // UP3
        document.getElementById('detail-up3').innerHTML = u.up3 && u.up3 !== '—'
            ? `<span class="mu-badge-up3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="12" height="12" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> ${escapeHtml(u.up3)}</span>`
            : '<span class="mu-empty-dash">—</span>';

        // Status
        const statusClass = u.status === 'active' ? 'active' : (u.status === 'pending' ? 'pending' : 'inactive');
        document.getElementById('detail-status').innerHTML = `
            <span class="mu-status-text ${statusClass}">${escapeHtml(u.status_label)}</span>
        `;

        // Registered
        document.getElementById('detail-registered').innerHTML = `
            <span>${escapeHtml(u.created_at)}</span>
            <span style="font-size: 11.5px; color: #94A3B8; font-weight: 500;">(${escapeHtml(u.created_at_human)})</span>
        `;

        // Terakhir Login Highlight
        const lastLoginEl = document.getElementById('detail-last-login');
        if (u.last_login_at) {
            lastLoginEl.innerHTML = `
                <span style="font-weight: 800; color: #023E8A; font-size: 13.5px;">
                    ${escapeHtml(u.last_login_at)}
                </span>
                <span style="font-size: 11.5px; font-weight: 700; color: #0081AB; background: #fff; padding: 2px 8px; border-radius: 6px; border: 1px solid #BAE6FD; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    ${escapeHtml(u.last_login_human)}
                </span>
            `;
        } else {
            lastLoginEl.innerHTML = `
                <span style="color: #94A3B8; font-weight: 500; font-size: 13px;">Belum pernah login ke sistem</span>
            `;
        }

        // Tab 1: Login Logs
        const loginLogs = data.login_logs || [];
        document.getElementById('count-login-logs').textContent = loginLogs.length;
        const listLoginLogs = document.getElementById('list-login-logs');
        const emptyLoginLogs = document.getElementById('empty-login-logs');

        if (loginLogs.length > 0) {
            emptyLoginLogs.style.display = 'none';
            listLoginLogs.style.display = 'block';
            listLoginLogs.innerHTML = loginLogs.map(log => {
                const isLogin = log.action === 'login';
                const dotClass = isLogin ? 'login' : 'logout';
                const iconSvg = isLogin
                    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>'
                    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>';
                const titleText = isLogin ? 'Sesi Login Berhasil' : 'Sesi Logout Berhasil';

                return `
                    <div class="mu-timeline-item">
                        <div class="mu-timeline-dot ${dotClass}">
                            ${iconSvg}
                        </div>
                        <div class="mu-timeline-card">
                            <div class="mu-timeline-header">
                                <span class="mu-timeline-title">${titleText}</span>
                                <span class="mu-timeline-time">${escapeHtml(log.waktu)} (${escapeHtml(log.time_ago)})</span>
                            </div>
                            <div class="mu-timeline-sub">
                                <span class="mu-timeline-meta-pill">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                    IP: <strong>${escapeHtml(log.ip)}</strong>
                                </span>
                                <span class="mu-timeline-meta-pill">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    Perangkat: <strong>${escapeHtml(log.device)}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            listLoginLogs.innerHTML = '';
            listLoginLogs.style.display = 'none';
            emptyLoginLogs.style.display = 'block';
        }

        // Tab 2: Change Logs
        const changeLogs = data.change_logs || [];
        document.getElementById('count-change-logs').textContent = changeLogs.length;
        const listChangeLogs = document.getElementById('list-change-logs');
        const emptyChangeLogs = document.getElementById('empty-change-logs');

        if (changeLogs.length > 0) {
            emptyChangeLogs.style.display = 'none';
            listChangeLogs.style.display = 'block';
            listChangeLogs.innerHTML = changeLogs.map(log => {
                let diffHtml = '';
                if (log.new_values && typeof log.new_values === 'object' && Object.keys(log.new_values).length > 0) {
                    const diffItems = [];
                    for (const key in log.new_values) {
                        const oldVal = (log.old_values && log.old_values[key] !== undefined) ? log.old_values[key] : null;
                        const newVal = log.new_values[key];
                        if (oldVal !== null && oldVal !== newVal) {
                            diffItems.push(`
                                <div class="mu-diff-item">
                                    <span class="mu-diff-label">${escapeHtml(key)}:</span>
                                    <span class="mu-diff-old">${escapeHtml(String(oldVal))}</span>
                                    <span class="mu-diff-arrow">&rarr;</span>
                                    <span class="mu-diff-new">${escapeHtml(String(newVal))}</span>
                                </div>
                            `);
                        } else if (newVal) {
                            diffItems.push(`
                                <div class="mu-diff-item">
                                    <span class="mu-diff-label">${escapeHtml(key)}:</span>
                                    <span class="mu-diff-new">${escapeHtml(String(newVal))}</span>
                                </div>
                            `);
                        }
                    }
                    if (diffItems.length > 0) {
                        diffHtml = `<div class="mu-diff-box">${diffItems.join('')}</div>`;
                    }
                }

                return `
                    <div class="mu-timeline-item">
                        <div class="mu-timeline-dot change">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </div>
                        <div class="mu-timeline-card">
                            <div class="mu-timeline-header">
                                <span class="mu-timeline-title">
                                    ${escapeHtml(log.action_label)}
                                    <span style="font-size: 11px; font-weight: 600; color: #64748B; background: #F1F5F9; padding: 1px 6px; border-radius: 4px;">${escapeHtml(log.model)}</span>
                                </span>
                                <span class="mu-timeline-time">${escapeHtml(log.waktu)} (${escapeHtml(log.time_ago)})</span>
                            </div>
                            <div class="mu-timeline-sub">
                                <span>Oleh: <strong>${log.is_actor ? 'Pengguna ini' : escapeHtml(log.actor_name)}</strong></span>
                            </div>
                            ${diffHtml}
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            listChangeLogs.innerHTML = '';
            listChangeLogs.style.display = 'none';
            emptyChangeLogs.style.display = 'block';
        }

        // Wire Edit Button from Detail
        editBtn.style.display = 'inline-flex';
        editBtn.onclick = function() {
            closeModal('modal-detail-user');
            const editForm = document.getElementById('edit-user-form');
            editForm.action = '/manajemen-user/' + u.id;
            document.getElementById('edit-user-name').value = u.name;
            document.getElementById('edit-user-role').value = u.role;
            document.getElementById('edit-user-up3').value = (u.up3 && u.up3 !== '—') ? u.up3 : '';
            document.getElementById('edit-user-email-display').textContent = u.email;
            openModal('modal-edit-user');
        };

        loadingEl.style.display = 'none';
        contentEl.style.display = 'block';
    })
    .catch(err => {
        console.error(err);
        loadingEl.innerHTML = `
            <div style="color: #EF4444; padding: 25px; text-align: center;">
                <svg style="width:32px; height:32px; margin:0 auto 8px; display:block;" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p style="font-weight:700; margin:0 0 4px;">Gagal memuat rincian pengguna</p>
                <p style="font-size:12px; color:#64748B; margin:0;">Silakan coba beberapa saat lagi.</p>
            </div>
        `;
    });
}

function switchDetailSubTab(tab) {
    const btnLogin = document.getElementById('tab-btn-login');
    const btnChanges = document.getElementById('tab-btn-changes');
    const paneLogin = document.getElementById('pane-detail-login');
    const paneChanges = document.getElementById('pane-detail-changes');

    if (tab === 'login') {
        btnLogin.classList.add('active');
        btnChanges.classList.remove('active');
        paneLogin.style.display = 'block';
        paneChanges.style.display = 'none';
    } else {
        btnLogin.classList.remove('active');
        btnChanges.classList.add('active');
        paneLogin.style.display = 'none';
        paneChanges.style.display = 'block';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-edit-user').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            var userName = this.getAttribute('data-name');
            var userEmail = this.getAttribute('data-email');
            var userRole = this.getAttribute('data-role');
            var userUp3 = this.getAttribute('data-up3') || '';

            var form = document.getElementById('edit-user-form');
            form.action = '/manajemen-user/' + userId;
            document.getElementById('edit-user-name').value = userName;
            document.getElementById('edit-user-role').value = userRole;
            document.getElementById('edit-user-up3').value = userUp3;
            document.getElementById('edit-user-email-display').textContent = userEmail;

            openModal('modal-edit-user');
        });
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('modal-tambah-user');
        closeModal('modal-edit-user');
        closeModal('modal-detail-user');
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