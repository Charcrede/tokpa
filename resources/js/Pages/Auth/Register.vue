<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from "vue";
import { onMounted } from 'vue';
import { watch } from 'vue';
import axios from 'axios';
defineProps(['message'])
const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    telephone: '',
    seller: '',
    birthdate: '',
    role: '',
    gender: '',
    preferences: "",
    password_confirmation: '',
});
let showPassword = ref(false);
let showVerifPw = ref(false);
let badPwd = ref('');
const mailRegex = /[a-zA-Z0-9_.\-]+@[a-zA-Z0-9_.\-]+\.[a-z­A-Z_.\-]{2,}/;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

const submit = () => {
    if (form.password === form.password_confirmation) {
        form.post('register', {
            onFinish: () => form.reset('password'),
        });
    } else {
        badPwd.value = 'pas identique'
    }
};


let current = ref(1);
let errorText = ref('');
function next() {

    if (current.value == 1) {
        if (form.firstname != '' && form.lastname != '' && form.birthdate < '2014-01-01' && form.birthdate != '') {
            let div = document.getElementById('div');
            div.scrollLeft = (div.offsetWidth + 32) * current.value;
            current.value++;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'entreés incorrect',
                text: 'Veuillez vous assurer de remplir tout les champs'
            })
        }
    } else if (current.value == 2) {
        if (mailRegex.test(form.email) && form.telephone != '' && form.telephone > 10000000 && form.telephone < 99999999) {
            axios.post('/getMail', { 'email': form.email, 'telephone': form.telephone }).then((resp) => {
                if (resp.data === 'good') {
                    current.value++;
                } else if (resp.data == 'mail') {
                    Swal.fire({
                        icon: 'error',
                        title: 'entreés incorrect',
                        text: 'Le mail que vous avez entrer est déjà attribué'
                    })
                } else if (resp.data == 'num') {
                    Swal.fire({
                        icon: 'error',
                        title: 'entreés incorrect',
                        text: 'Le numéro de téléphone que vous avez entrer est déjà attribué'
                    })
                }
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: 'entreés incorrect',
                text: 'Veuillez vous assurer de remplir tout les champs et que le mail et le téléphone entrés sont valides'
            })
        }
    } else if (current.value == 3) {
        if (passwordRegex.test(form.password) && form.password === form.password_confirmation) {
            let div = document.getElementById('div');
            div.scrollLeft = (div.offsetWidth + 32) * current.value;
            current.value++;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur mot de passe',
                text: 'Votre mot de passe doit contenir au minimum 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial et vous devez le répéter en bas'
            })
        }
    }
}
</script>



<template>
    <GuestLayout>
        <pre>{{ message }}</pre>
        <form action="" class="bg-[rgb(255,255,255)] rounded-2xl h-full header  mt-16" @submit.prevent="submit()">
            <legend class="text-2xl mb-4 text-center font-semibold">Inscrivez vous, c'est gratuit</legend>
            <div id="div" class="flex w-full gap-8 overflow-x-scroll h-full">
                <div class="min-w-full" v-if="current == 1">
                    <div class=" w-full">
                        <label class="my-2  block w-full  text-primary font-semibold" for="firstname"> Prénom (s)
                            :</label>
                        <input class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="text"
                            placeholder="ex : John" id="firstname" v-model="form.firstname" name="firstname" required>
                    </div>
                    <div class=" w-full">
                        <label class="my-2  block w-full  text-primary font-semibold" for="lastname">Nom :</label>
                        <input class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="text"
                            placeholder="ex : Doe" id="lastname" v-model="form.lastname" name="lastname" required>
                    </div>
                    <div class=" w-full">
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">Jour de naissance
                            :</label>
                        <input class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="date"
                            v-model="form.birthdate" name="birthdate" required>
                    </div>
                </div>
                <div class="min-w-full" v-if="current == 2">
                    <div class=" w-full">
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">email :</label>
                        <input class="block border-b-[#505050] shadow-xl border-b p-4 w-full"
                            pattern="[a-zA-Z0-9_.\-]+@[a-zA-Z0-9_.\-]+\.[a-z­A-Z_.\-]{2,}" type="mail"
                            placeholder="ex : johnDoe@gmail.com" v-model="form.email" name="email" required>
                    </div>
                    <div class=" w-full">
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">numéro de téléphone
                            :</label>
                        <div class="flex">
                            <div class="block border-b-[#505050] shadow-xl border-b p-4 w-12">
                                <img src="../../../../storage/app/assets/image/benin.png">
                            </div>
                            <input type="number" id="phoneNumber" placeholder="ex : 40460010"
                                class="block border-b-[#505050] shadow-xl border-b p-4 w-full" required
                                v-model="form.telephone" name="telephone">
                        </div>
                    </div>
                </div>
                <div class="min-w-full" v-if="current == 3">
                    <div class="relative  w-full" v-if="showPassword">
                        <button class="absolute top-[50%] flex justify-center items-center right-4"
                            @click.prevent="showPassword = !showPassword"><svg fill="#000" width="28" height="28"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z" />
                            </svg></button>
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">mot de passe
                            :</label>
                        <input
                            title="Ce champ doit contenir au moins une majuscule un caractère spéciale un chiffre et 8 caractère au total"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                            class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="text"
                            placeholder="mot de passe" v-model="form.password" name="password" required>
                    </div>
                    <div class="relative  w-full" v-if="!showPassword">
                        <button class="absolute top-[50%] flex justify-center items-center right-4"
                            @click.prevent="showPassword = !showPassword"><svg fill="#000" width="28" height="28"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                            </svg></button>
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">mot de passe
                            :</label>
                        <input
                            title="Ce champ doit contenir au moins une majuscule un caractère spéciale un chiffre et 8 caractère au total"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                            class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="password"
                            placeholder="mot de passe" v-model="form.password" name="password" required>
                    </div>
                    <div class="relative  w-full" v-if="showVerifPw">
                        <button class="absolute top-[50%] flex justify-center items-center right-4"
                            @click.prevent="showVerifPw = !showVerifPw"><svg fill="#000" width="28" height="28"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z" />
                            </svg></button>
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">confirmer le mot de
                            passe
                            :</label>
                        <input
                            title="Ce champ doit contenir au moins une majuscule un caractère spéciale un chiffre et 8 caractère au total"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                            class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="text"
                            placeholder="confirmer le mot de passe" v-model="form.password_confirmation" name="password"
                            required>
                    </div>
                    <div class="relative  w-full" v-if="!showVerifPw">
                        <button class="absolute top-[50%] flex justify-center items-center right-4"
                            @click.prevent="showVerifPw = !showVerifPw"><svg fill="#000" width="28" height="28"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path
                                    d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                            </svg></button>
                        <label class="my-2  block w-full  text-primary font-semibold" for="birthdate">confirmer le mot de
                            passe
                            :</label>
                        <input
                            title="Ce champ doit contenir au moins une majuscule un caractère spéciale un chiffre et 8 caractère au total"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                            class="block border-b-[#505050] shadow-xl border-b p-4 w-full" type="password"
                            placeholder="confirmer le mot de passe" v-model="form.password_confirmation" name="password"
                            required>
                    </div>
                </div>
                <div class="min-w-full" v-if="current == 4">
                    <div class="m-2 border-b border-b-[#505050] py-2 shadow-xl">
                        <p class="text-primary font-semibold mb-2">type de compte :</p>
                        <div class="flex gap-4">
                            <div class="flex justify-center items-center gap-2">
                                <input id="seller" class="w-6 h-6 text-secondary focus:ring-secondary" type="radio"
                                    name="seller" v-model="form.seller" value="1" required>
                                <label for="seller" class="text-xl">vendeur</label>
                            </div>
                            <div class="flex justify-center items-center gap-2">
                                <input id="baier" class="w-6 h-6 text-secondary focus:ring-secondary" type="radio"
                                    name="seller" v-model="form.seller" value="0" required>
                                <label for="baier" class="text-xl">acheteur</label>
                            </div>
                        </div>
                    </div>
                    <div class="m-2 border-b border-b-[#505050] py-2 shadow-xl">
                        <p class="text-primary font-semibold mb-2">Voues êtes :</p>
                        <div class="flex gap-4">
                            <div class="flex justify-center items-center gap-2">
                                <input id="M" class="w-6 h-6 text-secondary focus:ring-secondary" type="radio" name="gender"
                                    v-model="form.gender" value="M" required>
                                <label for="M" class="text-xl">Homme</label>
                            </div>
                            <div class="flex justify-center items-center gap-2">
                                <input id="F" class="w-6 h-6 text-secondary focus:ring-secondary" type="radio" name="gender"
                                    v-model="form.gender" value="F" required>
                                <label for="F" class="text-xl">Femme</label>
                            </div>
                        </div>
                    </div>
                    <button
                        class="text-white font-semibold rounded-3xl px-4 py-2 mt-6 w-full block bg-primary">Envoyer</button>
                </div>
            </div>
            <PrimaryButton @click.prevent="next()" v-if="current != 4 && current != 2">Suivant</PrimaryButton>
            <PrimaryButton @click.prevent="next()" v-if="current === 2">Suivant</PrimaryButton>
        </form>

        <p class="m-4">Vous avez déjà un compte ? <a class="text-primary" href="/login"> Se connecter</a></p>
    </GuestLayout>
</template>