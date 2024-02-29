<script>
import { Link } from '@inertiajs/vue3';
import Logo from '@/Icons/Logo.vue'
import LogoTS from '@/Icons/LogoTS.vue'
import LogoNom from '@/Icons/LogoNom.vue'
import Icon from '@/Icons/Icon.vue'
export default {
    data() {
        return {
            textValues: ['acheter', 'vendre', 'découvrir'],
            text: "acheter",
            i: 1,
        }
    },
    components: {
        Link,
        Logo,
        LogoTS,
        LogoNom,
        Icon,
    },
    mounted() {
        setInterval(() => {
            if (this.i < this.textValues.length) {
                this.text = this.textValues[this.i];
                this.i++
            } else {
                this.i = 0
                this.text = this.textValues[this.i];
                this.i++;
            }
        }, 1000);
    }
}
</script>

<template>
    <div class="header w-full">
        <nav class=" p-2">
            <img src="../../../storage/app/assets/image/bg-header-desktop.png" class="w-full absolute top-0 hidden lg:block">
            <img src="../../../storage/app/assets/image/bg-header-mobile.png" class="w-full absolute top-0 lg:hidden">
            <div class="flex justify-center mt-4 items-start">
                <Logo width="200" height="200"></Logo>
            </div>
        </nav>
        <div class="overflow-hidden duration-1000 p-4 flex flex-col justify-start items-left">
            <p class="duration-1000 bg-opacity-50 flex text-[40px] font-bold font-economica items-center justify-center">
                Venez {{ text }}
            </p>
            <div class="mt-4 pl-2 text-xl text-center">
                <p>Le premier marché en ligne exclusivement béninois</p>
                <div class="lg:flex lg:gap-2 lg:justify-center">
                    <Link href="/login" class="lg:w-1/4 text-primary hover:text-white mt-2 cursor-pointer hover:bg-primary border border-primary rounded-3xl py-2 px-4 w-full block text-center">Se connecter</Link>
                    <Link href="/signIn" class="lg:w-1/4 text-white font-semibold mt-2 rounded-3xl px-4 py-2 block bg-primary">S'inscrire
                        maitenant</Link>
                    </div>
            </div>
        </div>
    </div>
    <div class="main lg:flex gap-4 mb-8">
        <section class="text-center bg-white m-2 p-2 rounded-2xl shadow-xl">
            <h2 class="font-bold text-3xl">Publiez</h2>
            <p class="mt-2 text-xl">Créer votre boutique et publiés vos articles</p>
            <img class="mt-2" src="../../../storage/app/assets/image/illustration-grow-together.svg" alt="">
        </section>
        <section class="text-center bg-white m-2 p-2 rounded-2xl shadow-xl">
            <h2 class="font-bold text-3xl">Découvrez</h2>
            <p class="mt-2 text-xl">Venez découvrir des articles et des services de tous genres proposé par diverses
                boutiques béninoise</p>
            <img class="mt-2" src="../../../storage/app/assets/image/illustration-your-users.svg" alt="">
        </section>
    </div>
    <div class="footer text-white">
        <img src="../../../storage/app/assets/image/bg-footer-top-mobile.svg" class="w-full lg:hidden" alt="">
        <img src="../../../storage/app/assets/image/bg-footer-top-desktop.svg" class="w-full hidden lg:block" alt="">
        <div class="bg-secondary relative bottom-1">
            <div class="flex justify-center items-center">
                <LogoTS fill="#fff" width="100" height="100"></LogoTS>
                <LogoNom fill="#fff" class="relative left-[-30px] top-[-37.5px]" width="100" height="100"></LogoNom>
            </div>
            <p class="text-center p-4">Tokpa.shop ne garantit pas l'authenticité des boutiques présentes sur son site, nous vous conseillons de finaliser vos premières transactions en personnes ou de vous assurer de la crédibilité de la boutique avec laquelle vous traiter avant d'envoyer votre argent !</p>
            <div class="px-8 lg:flex lg:justify-evenly lg:items-center lg:py-4">
                <div class="flex gap-4 mb-4">
                    <Icon :name="'phone-vol'" width="30" height="30"></Icon>
                    <span class="text-xl font-bold">Tél : +229 40460010</span>
                </div>
                <div class="flex gap-4 mb-4">
                    <Icon :name="'mail'" width="30" height="30"></Icon>
                    <span class="text-xl font-bold">tokpashoponline@gmail.com</span>
                </div>
                <div class="flex gap-4 pb-4 mt-8 lg:mt-0">
                    <Icon :name="'facebook'" width="30" height="30"></Icon>
                    <Icon :name="'whatap'" width="30" height="30"></Icon>
                </div>
            </div>
        </div>
    </div>
</template>
