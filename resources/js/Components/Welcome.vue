<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
  <div class="bg-[#F9FAFB] min-h-screen">
    <!-- Hero Section -->
    <div class="bg-[#1F2937] text-white py-12 px-6 lg:px-20 text-center">
      <ApplicationLogo class="mx-auto h-16 w-auto" />

      <h1 class="mt-6 text-4xl font-extrabold tracking-tight">
        Train With Purpose. Manage With Power.
      </h1>

      <p class="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">
        Your complete Gym management solution for members, coaches, and administrators.
      </p>

      <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
        <a href="/login" class="px-6 py-3 bg-[#10B981] text-white font-semibold rounded-xl hover:bg-[#0ea672] transition">
          Join Now
        </a>
        <a href="javascript:void(0)" class="px-6 py-3 bg-[#3B82F6] text-white font-semibold rounded-xl opacity-50 cursor-not-allowed">
          Register
        </a>

        <a href="#programs" class="px-6 py-3 border border-white text-white font-semibold rounded-xl hover:bg-white hover:text-[#1F2937] transition">
          Explore Programs
        </a>
      </div>
    </div>

    <!-- Info Grid -->
    <div class="bg-gray-100 py-10 px-6 lg:px-20 grid grid-cols-1 md:grid-cols-2 gap-10" id="programs">
      <!-- Documentation -->
      <div class="bg-white p-6 rounded-2xl shadow hover:shadow-md transition">
        <div class="flex items-center gap-3">
          <svg class="w-6 h-6 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967..." />
          </svg>
          <h2 class="text-xl font-bold text-gray-900">
            <a href="https://laravel.com/docs">Documentation</a>
          </h2>
        </div>
        <p class="mt-4 text-gray-600 text-sm leading-relaxed">
          Laravel's complete documentation for building powerful applications.
        </p>
        <a href="https://laravel.com/docs" class="mt-4 inline-flex items-center font-semibold text-[#10B981] hover:underline">
          Explore the documentation
          <svg class="ml-1 w-5 h-5 fill-[#10B981]" viewBox="0 0 20 20">
            <path d="M5 10a.75.75..."/>
          </svg>
        </a>
      </div>

      <!-- Laracasts -->
      <div class="bg-white p-6 rounded-2xl shadow hover:shadow-md transition">
        <div class="flex items-center gap-3">
          <svg class="w-6 h-6 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72..." />
          </svg>
          <h2 class="text-xl font-bold text-gray-900">
            <a href="https://laracasts.com">Laracasts</a>
          </h2>
        </div>
        <p class="mt-4 text-gray-600 text-sm leading-relaxed">
          Learn Laravel, PHP and JS with top-quality video tutorials.
        </p>
        <a href="https://laracasts.com" class="mt-4 inline-flex items-center font-semibold text-[#10B981] hover:underline">
          Watch Laracasts
          <svg class="ml-1 w-5 h-5 fill-[#10B981]" viewBox="0 0 20 20">
            <path d="..." />
          </svg>
        </a>
      </div>

      <!-- Tailwind -->
      <div class="bg-white p-6 rounded-2xl shadow hover:shadow-md transition">
        <div class="flex items-center gap-3">
          <svg class="w-6 h-6 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M2.25 15.75..." />
          </svg>
          <h2 class="text-xl font-bold text-gray-900">
            <a href="https://tailwindcss.com/">Tailwind</a>
          </h2>
        </div>
        <p class="mt-4 text-gray-600 text-sm leading-relaxed">
          Clean, fast, and beautiful designs made easy with Tailwind CSS.
        </p>
      </div>

      <!-- Authentication -->
      <div class="bg-white p-6 rounded-2xl shadow hover:shadow-md transition">
        <div class="flex items-center gap-3">
          <svg class="w-6 h-6 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M16.5 10.5V6.75..." />
          </svg>
          <h2 class="text-xl font-bold text-gray-900">
            Authentication
          </h2>
        </div>
        <p class="mt-4 text-gray-600 text-sm leading-relaxed">
          Secure login, registration, and account management with Jetstream.
        </p>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#1F2937] text-gray-300 py-6 text-center text-sm">
      <p>&copy; 2025 GymPower. All rights reserved.</p>
    </footer>
  </div>
</template>