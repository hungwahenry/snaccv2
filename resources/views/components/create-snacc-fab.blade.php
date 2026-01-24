<!-- FAB (Floating Action Button) for Create Snacc -->
<button
    type="button"
    x-data="{
        visible: true,
        scrollTimeout: null,
        side: localStorage.getItem('fab-side') || 'right',
        isDragging: false,
        startX: 0,
        currentX: 0,
        init() {
            window.addEventListener('scroll', () => {
                this.visible = false;
                clearTimeout(this.scrollTimeout);
                this.scrollTimeout = setTimeout(() => {
                    this.visible = true;
                }, 300);
            });
        },
        startDrag(e) {
            this.isDragging = true;
            this.startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            this.currentX = this.startX;
        },
        drag(e) {
            if (!this.isDragging) return;
            e.preventDefault();
            this.currentX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
        },
        endDrag() {
            if (!this.isDragging) return;
            this.isDragging = false;
            
            const dragDistance = this.currentX - this.startX;
            const screenWidth = window.innerWidth;
            const threshold = screenWidth / 4;
            
            // Determine which side based on drag direction and current position
            if (this.side === 'right' && dragDistance < -threshold) {
                this.side = 'left';
            } else if (this.side === 'left' && dragDistance > threshold) {
                this.side = 'right';
            }
            
            localStorage.setItem('fab-side', this.side);
            this.currentX = this.startX;
        }
    }"
    @click="if (!isDragging) $dispatch('open-modal', 'create-snacc')"
    @mousedown="startDrag($event)"
    @mousemove="drag($event)"
    @mouseup="endDrag()"
    @mouseleave="endDrag()"
    @touchstart="startDrag($event)"
    @touchmove="drag($event)"
    @touchend="endDrag()"
    class="fixed bottom-20 lg:bottom-6 z-40 w-14 h-14 bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center group touch-none select-none"
    :class="{
        'right-4 lg:right-6': side === 'right',
        'left-4 lg:left-6': side === 'left',
        'translate-x-24': !visible && side === 'right',
        '-translate-x-24': !visible && side === 'left',
        'cursor-move': isDragging,
        'transition-all duration-200': !isDragging
    }"
    :style="isDragging ? `transform: translateX(${currentX - startX}px)` : ''"
    aria-label="Create Snacc"
>
    <x-solar-magic-stick-3-bold class="w-6 h-6 group-hover:scale-110 transition-transform" />
</button>
