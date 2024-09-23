<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>

                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('User Information') }}
                            </h2>

                        </header>

                        <form method="post" action="{{ route('projects.update',['project'=>$project->id]) }}" class="mt-6 space-y-6">

                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name',$project->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="Description" :value="__('Description')" />
                                <textarea name="description" class="mt-1 block w-full" rows="4" cols="50">{{$project->description}}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <div>
                                    <x-input-label for="start_date" :value="__('Start Date')" />
                                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block" :value="old('start_date',$project->start_date)" required autofocus autocomplete="start_date" />
                                    <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                                </div>
                                <div>
                                    <x-input-label for="end_date" :value="__('Name')" />
                                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block" :value="old('end_date',$project->end_date)" required autofocus autocomplete="end_date" />
                                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="Users" :value="__('Assign Users')" />
                                <select class="select2 mt-1 block w-full" name="users[]" multiple>
                                    @foreach($users as $user)
                                    <option @if(in_array($user->id, $project->users->pluck('id')->toArray())) selected @endif value="{{ $user->id }}">
                                        #{{ $user->id . ' ' . $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>


                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>

                        </form>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>