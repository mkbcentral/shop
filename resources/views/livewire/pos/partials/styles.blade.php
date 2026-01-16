<!-- Custom Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
        }
    }

    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scale-in {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @keyframes bounce-once {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    @keyframes slide-in-right {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .animate-fade-in {
        animation: fade-in 0.2s ease-out;
    }

    .animate-scale-in {
        animation: scale-in 0.3s ease-out;
    }

    .animate-bounce-once {
        animation: bounce-once 0.6s ease-out;
    }

    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }

    /* Scrollbar styling */
    .custom-scrollbar::-webkit-scrollbar {
        width: 12px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
        margin: 8px 0;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #818cf8, #a855f7);
        border-radius: 10px;
        border: 2px solid #f1f5f9;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #6366f1, #9333ea);
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #818cf8, #a855f7);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #6366f1, #9333ea);
    }
</style>
