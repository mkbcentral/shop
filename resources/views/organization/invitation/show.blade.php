<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Organization Invitation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Organization Info -->
                    <div class="text-center mb-8">
                        @if($invitation->organization->logo)
                            <img src="{{ asset('storage/' . $invitation->organization->logo) }}" 
                                 alt="{{ $invitation->organization->name }}" 
                                 class="w-20 h-20 mx-auto mb-4 rounded-full">
                        @else
                            <div class="w-20 h-20 mx-auto mb-4 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    {{ substr($invitation->organization->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $invitation->organization->name }}
                        </h3>
                        
                        <p class="text-gray-600 mb-4">
                            You've been invited to join as a <span class="font-semibold">{{ ucfirst($invitation->role) }}</span>
                        </p>

                        @if($invitation->message)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 text-left">
                                <p class="text-sm text-gray-700">{{ $invitation->message }}</p>
                            </div>
                        @endif

                        <p class="text-sm text-gray-500">
                            Invited by: 
                            <span class="font-medium">{{ $invitation->inviter->name ?? 'Organization Admin' }}</span>
                        </p>
                        
                        <p class="text-sm text-gray-500 mt-1">
                            Expires: {{ $invitation->expires_at->format('F j, Y') }}
                        </p>
                    </div>

                    <!-- Error/Success Messages -->
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-4 justify-center">
                        <form method="POST" action="{{ route('organization.invitation.accept', $invitation->token) }}">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                Accept Invitation
                            </button>
                        </form>

                        <form method="POST" action="{{ route('organization.invitation.decline', $invitation->token) }}" 
                              onsubmit="return confirm('Are you sure you want to decline this invitation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition">
                                Decline
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            By accepting this invitation, you'll be able to:
                        </p>
                        <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                            <li>Access {{ $invitation->organization->name }}'s workspace</li>
                            <li>Collaborate with team members</li>
                            <li>Manage organization resources based on your role</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
