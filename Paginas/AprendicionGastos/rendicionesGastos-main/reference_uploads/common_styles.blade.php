    *, *::before, *::after {
        box-sizing: border-box;
    }

    :root {
        --bg: #f5f7fb;
        --bg-accent: #eef2ff;
        --ink: #101828;
        --muted: #5b6473;
        --brand: #0f6bb6;
        --brand-2: #0a4f86;
        --line: #e3e8f0;
        --card: #ffffff;
        --chip: #e8f1fb;
        --success: #0f7a3e;
        --warning: #b97700;
    }

    /* Transiciones suaves */
    .sidebar, .sidebar *, .nav-label, .brand-text {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Prevenir salto visual al cargar la página */
    .sidebar.is-initializing, .sidebar.is-initializing * {
        transition: none !important;
    }

    /* SideBar Base */
    .sidebar {
        background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
        color: #fff;
        width: 260px;
        display: flex;
        flex-direction: column;
        box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
        position: relative;
        z-index: 100;
        min-height: 100vh;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    /* Sidebar Header & Brand */
    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 88px;
    }

    .sidebar.collapsed .sidebar-header {
        padding: 24px 10px;
        justify-content: center;
    }

    .sidebar .brand-badge {
        width: 80px !important;
        height: 60px !important;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar .brand-badge img {
        width: 100%;
        height: 100%;
        max-height: 48px;
        object-fit: contain;
    }

    .sidebar.collapsed .brand-text {
        opacity: 0;
        visibility: hidden;
        position: absolute;
    }

    /* Nav Items */
    .sidebar-nav {
        flex: 1;
        padding: 12px 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 20px;
        margin: 4px 12px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        position: relative;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, 0.1); 
        color: #fff;
        font-weight: 700;
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 60%;
        background: #fff;
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .sidebar.collapsed .nav-label {
        opacity: 0;
        visibility: hidden;
        position: absolute;
    }

    .sidebar.collapsed .nav-item {
        justify-content: center;
        padding: 12px;
        margin: 6px 14px;
    }

    .sidebar.collapsed .nav-item.active {
        background: transparent;
        color: #fff;
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
    }

    .sidebar.collapsed .nav-item.active::before {
        display: none;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        stroke-width: 2;
    }

    /* Toggle Button */
    .toggle-btn {
        position: fixed;
        bottom: 24px;
        left: 12px;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        display: grid;
        place-items: center;
        cursor: pointer;
        backdrop-filter: blur(10px);
        z-index: 200;
    }

    .sidebar.collapsed .toggle-btn {
        left: 20px;
    }

    .toggle-icon {
        width: 20px;
        height: 20px;
        stroke: #fff;
    }

    .sidebar.collapsed .toggle-icon {
        transform: rotate(180deg);
    }

    /* Layout Base */
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        position: relative;
    }

    body {
        background-color: var(--bg);
        font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
    }

    .page {
        display: flex;
        flex-direction: row;
        min-height: 100vh;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        background: var(--bg);
    }

    .main-content {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        min-width: 0;
        overflow-x: hidden;
        position: relative;
        background: #f8fafc;
    }

    @media (max-width: 768px) {
        .sidebar { display: none; }
    }

    /* Botones globales */
    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        color: #fff !important;
        border: 1px solid #1e40af !important;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3) !important;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a) !important;
        transform: translateY(-2px);
    }

    .btn-ghost {
        background: #fff !important;
        color: #2563eb !important;
        border: 1.5px solid #bfdbfe !important;
    }

    .btn-ghost:hover {
        background: #eff6ff !important;
        border-color: #3b82f6 !important;
        transform: translateY(-2px);
    }

    /* TopBar & Header */
    .topbar {
        background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
        color: white;
        padding: 12px 20px;
        box-shadow: 0 4px 20px rgba(15, 107, 182, 0.15);
        position: sticky;
        top: 0;
        z-index: 90;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        min-height: 70px;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .topbar-inner {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .brand img {
        height: 45px;
        width: auto;
        object-fit: contain;
    }

    .toolbar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Standardized Logout Button (Solid Red Premium) */
    .btn-logout, .nav-item-logout {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 9px !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        text-decoration: none !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
    }

    .btn-logout:hover, .nav-item-logout:hover {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(220, 38, 38, 0.35) !important;
    }

    /* Sidebar Specific Logout Button Correction */
    .nav-item-logout {
        margin: 16px 12px !important;
        width: calc(100% - 24px) !important;
        justify-content: flex-start;
        height: 42px !important;
    }

    .sidebar.collapsed .nav-item-logout {
        width: 50px !important;
        height: 50px !important;
        margin: 16px auto !important;
        padding: 0 !important;
        justify-content: center;
        border-radius: 12px !important;
    }

    /* Estilos estandarizados para formularios (Cliente, Interna, Negocio) */
    .content {
        width: auto;
        flex: 1;
        margin: 0;
        padding: 24px 40px 60px;
    }

    .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        box-shadow: 0 14px 30px rgba(16, 24, 40, 0.08);
        overflow: hidden;
        width: 100%;
    }

    .form-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
    }

    .form-header h1 {
        margin: 0 0 4px;
        font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
        font-size: 22px;
        font-weight: 600;
        color: var(--ink);
    }

    .form-header p {
        margin: 0;
        font-size: 14px;
        color: var(--muted);
    }

    .form-body {
        padding: 32px 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-field.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .required {
        color: #e11d48;
    }

    .input, .select, .textarea {
        padding: 11px 14px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        font-size: 14px;
        color: var(--ink);
        font-family: inherit;
        transition: all 0.2s ease;
        width: 100%;
    }

    .input:focus, .select:focus, .textarea:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
    }

    .textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Premium Modal System */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        display: none; /* Se maneja con JS: flex o none */
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        z-index: 99999;
        overflow-y: auto;
    }

    .modal-container {
        background: #ffffff;
        width: 100%;
        max-width: 650px;
        max-height: calc(100vh - 80px);
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.95);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        margin: auto;
    }

    .modal-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px 28px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .modal-close {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
    }

    .modal-close:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: rotate(90deg);
        border-color: #cbd5e1;
    }

    .modal-body {
        padding: 28px;
        overflow-y: auto;
        background: #ffffff;
        flex: 1;
    }

    .modal-footer {
        padding: 18px 28px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Input premium within modals */
    .modal-body .input, .modal-body .textarea {
        border-width: 1.5px;
        border-color: #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
    }

    @media (max-width: 768px) {
        .topbar { padding: 12px 20px; }
        .topbar-inner { gap: 10px; }
        .brand img { height: 35px; }
        .brand { flex-direction: column; align-items: flex-start; gap: 4px; }
        .content { padding: 16px; }
        .form-grid { grid-template-columns: 1fr; }
    }

    .swal2-container { z-index: 10000 !important; }
