<x-app-layout transparent-header="true">
    <div>
        <!-- Profile Header -->
        <x-profile.header 
            :user="$user" 
            :profile="$profile" 
            :isOwnProfile="$isOwnProfile" 
            :isAdded="$isAdded" 
        />

        <!-- Feed Content -->
        <div class="mt-0">
             <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-border">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white lowercase tracking-wider">Snaccs</h3>
             </div>

             <div id="user-feed-container">
                 <x-posts.feed-list :snaccs="$snaccs" />
             </div>

             <!-- Infinite Scroll -->
             @if($snaccs->hasMorePages())
                <x-infinite-scroll 
                    nextUrl="{{ $snaccs->nextPageUrl() }}" 
                    container="#user-feed-container" 
                />
             @endif
        </div>
    </div>

    <!-- Report User Modal -->
    <x-reports.modal
        modalName="report-user-modal"
        reportableType="user"
        :reportableSlug="$user->profile->username"
    />
</x-app-layout>
