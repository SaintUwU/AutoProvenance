<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Web3 from 'web3';



defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    
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

async function loginWeb3() {
    if (!window.ethereum) {
        alert('MetaMask not detected. Please try again from a MetaMask enabled browser.');
        return;
    }

    const web3 = new Web3(window.ethereum);

    const message = [
        "I have read and accepted the terms and conditions (https://AutoProvenance.com/) of this app.",
        "Please sign me in!"
    ].join("\n");

    const address = (await web3.eth.requestAccounts())[0];
    const signature = (await web3.eth.personal.sign(message, address));
    return useForm({ message, address, signature }).post('/login-web3');
    
};

</script>

<template>
    <Head title="Lockscreen" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />

        </template>

        <div class="text-center pt-4 pb-8 border-b border-gray-200">
    
</div>
<div class="py-6 text-sm text-gray-500 text-center">
    
</div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
           

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Unlock
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
