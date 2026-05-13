<svg {{ $attributes }} viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="medHeartGradient" x1="8" y1="8" x2="56" y2="56" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#10B981" />
            <stop offset="50%" stop-color="#06B6D4" />
            <stop offset="100%" stop-color="#3B82F6" />
        </linearGradient>

        <filter id="medHeartGlow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="2.5" result="blur"/>
            <feMerge>
                <feMergeNode in="blur"/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>
    </defs>

    <!-- Outer medical shield -->
    <rect x="6" y="6" width="52" height="52" rx="16"
          stroke="url(#medHeartGradient)"
          stroke-width="2.5"
          opacity="0.25"/>

    <!-- Heart shape -->
    <path
        d="M32 50
           C30 48, 17 38, 17 26
           C17 19.5, 21.5 15, 27.5 15
           C31 15, 34 16.8, 36 20
           C38 16.8, 41 15, 44.5 15
           C50.5 15, 55 19.5, 55 26
           C55 38, 42 48, 40 50
           C37.5 52.3, 34.5 53, 32 50Z"
        fill="url(#medHeartGradient)"
        filter="url(#medHeartGlow)"
    />

    <!-- Medical cross inside heart -->
    <rect x="29" y="23" width="6" height="18" rx="2" fill="white"/>
    <rect x="23" y="29" width="18" height="6" rx="2" fill="white"/>
</svg>