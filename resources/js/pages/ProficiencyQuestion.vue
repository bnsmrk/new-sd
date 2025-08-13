<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const form = useForm({
    aiken_file: null as File | null,
});

const submit = () => {
    form.post(route('proficiency-questions.store'), {
        onSuccess: () => {
            form.reset('aiken_file');
        },
        onError: () => {
            console.error('An error occurred during file upload.');
        },
    });
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        form.aiken_file = target.files[0];
    }
};
</script>

<template>
    <Head title="Upload Aiken Quiz" />
    <AppLayout>
        <div class="font-inter flex min-h-screen items-center justify-center bg-gray-100 p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl">
                <div class="px-6 py-8 sm:p-10">
                    <h1 class="mb-6 text-center text-3xl font-bold text-gray-900">Upload Aiken Quiz File (Proficiency)</h1>
                    <p class="mb-8 text-center text-gray-600">Upload a `.txt` file containing quiz questions in the Aiken format.</p>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="aiken_file" class="block text-sm font-medium text-gray-700"> Select Aiken File </label>
                            <div class="mt-2 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.293-3.293A4 4 0 0028.707 24H22.5"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label
                                            for="file-upload"
                                            class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:outline-none hover:text-indigo-500"
                                        >
                                            <span>{{ form.aiken_file ? form.aiken_file.name : 'Upload a file' }}</span>
                                            <input
                                                id="file-upload"
                                                name="aiken_file"
                                                type="file"
                                                class="sr-only"
                                                @change="handleFileChange"
                                                accept=".txt"
                                            />
                                        </label>
                                        <p v-if="!form.aiken_file" class="pl-1">or drag and drop</p>
                                    </div>
                                    <p v-if="form.aiken_file" class="text-xs text-gray-500">
                                        {{ form.aiken_file.name }}
                                    </p>
                                    <p v-else class="text-xs text-gray-500">TXT up to 2MB</p>
                                </div>
                            </div>
                            <div v-if="form.errors.aiken_file" class="mt-2 text-sm text-red-600">
                                {{ form.errors.aiken_file }}
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-sm font-medium text-white shadow-sm transition-colors duration-200 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                                :class="{ 'cursor-not-allowed opacity-50': form.processing || !form.aiken_file }"
                                :disabled="form.processing || !form.aiken_file"
                            >
                                <span v-if="form.processing">Processing...</span>
                                <span v-else>Upload and Save Questions</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
