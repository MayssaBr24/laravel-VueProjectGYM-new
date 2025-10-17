<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login | PowerGym" />

    <div class="min-h-screen flex items-center justify-center bg-gray-900 p-6 relative overflow-hidden">
        <!-- Effet néon vert -->
        <div class="absolute w-80 h-80 bg-green-500 rounded-full filter blur-3xl opacity-10 animate-pulse"></div>

        <div class="w-full max-w-md bg-gray-800 rounded-xl shadow-2xl border border-gray-700 p-8 space-y-6 z-10">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Welcome Back!</h1>
                <p class="text-gray-400 mt-2">Log in to track your workouts and progress.</p>
            </div>

            <div v-if="status" class="text-green-400 text-sm font-medium text-center p-3 bg-green-900/30 rounded-lg">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <InputLabel for="email" value="Email" class="text-gray-300" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-green-500 focus:ring-green-500 rounded-lg"
                        placeholder="your@email.com"
                        required
                        autofocus
                    />
                    <InputError class="mt-1 text-sm text-green-400" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="text-gray-300" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-green-500 focus:ring-green-500 rounded-lg"
                        placeholder="••••••••"
                        required
                    />
                    <InputError class="mt-1 text-sm text-green-400" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2">
                        <Checkbox v-model:checked="form.remember" name="remember" class="border-gray-600 focus:ring-green-500" />
                        <span class="text-sm text-gray-300">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-green-400 hover:text-green-300 transition"
                    >
                        Forgot password?
                    </Link>
                </div>

                <PrimaryButton
                    class="w-full flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg"
                    :class="{ 'opacity-70': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-else>Log In</span>
                </PrimaryButton>
            </form>

            
        </div>
    </div>
</template>