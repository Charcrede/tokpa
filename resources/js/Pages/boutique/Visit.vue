<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link } from "@inertiajs/vue3";
import Icon from "@/Icons/Icon.vue"
import axios from "axios";

export default {
    props: {
        boutique: Array | String,
        logo: Array | undefined,
        banner: Array | undefined,
        propsArticles: Array | undefined,
        articlesCount: Number,
    },
    components: {
        AuthenticatedLayout,
        PrimaryLink,
        PrimaryButton,
        Icon,
        Link,
    },
    data() {
        return {
            pathToMedia: "../../../../public/boutique_medias/",
            // pathToMedia : 'C:/Users/Lonely wild/Desktop/tokpa/public/boutique_medias',
            openDesc: false,
            path: '',
            display: 'plusRecent',
            num: 9,
            articles: [],
            div : '',
        }
    },
    mounted() {
        this.articles = this.propsArticles;
        this.div = document.querySelector('.grid');
    },
    methods: {
        getPath(type, name) {
            if (type === 'boutique') {
                return new URL("../../../../public/boutique_medias/" + name, import.meta.url).href;
            } else if (type === 'article') {
                return new URL("../../../../public/article_medias/" + name, import.meta.url).href
            }
        },
        showMore(){
            this.num += 9;
            let div = document.querySelector('.grid');
            axios.post(`/plusRecent`, {
                'num' : this.num,
                'boutique': this.boutique
            }).then((resp) => {
                this.articles = resp.data
                setTimeout(() => {
                    div.scrollTop = div.scrollHeight
                }, 100);
            })
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <div class="flex justify-evenly px-4 mt-4 gap-4 items-center lg:flex-col">
                <div class="w-20 h-20 overflow-hidden rounded-[50%] flex justify-center items-center relative lg:w-60 lg:h-60">
                    <img v-if="logo[0].path" id="logo"
                        class="h-full border-2 border-primary rounded-full p-[0.12rem] w-full"
                        :src="getPath('boutique', logo[0].path)" :alt="pathToMedia + logo[0].path">
                    <div v-else
                        class="absolute top-0 bottom-0 left-0 right-0 border-4 border-[#aaa] rounded-[50%] flex justify-center items-center overflow-hidden">
                        <Icon name="shop" width="80" height="80" fill="#aaa"></Icon>
                    </div>
                </div>

                <div class="flex gap-4 mr-4 lg:justify-evenly lg:w-full">
                    <div class="flex flex-col items-center text-center text-xl">
                        <span class="font-bold">{{ articlesCount }}</span>
                        <span class="text-[#888]">articles</span>
                    </div>
                    <div class="flex flex-col items-center text-center text-xl">
                        <span class="font-bold">{{ boutique.visit }}</span>
                        <span class="w-16 text-[#888]">visites</span>
                    </div>
                    <div class="flex flex-col items-center text-center text-xl">
                        <span class="font-bold">{{ boutique.commande }}</span>
                        <span class="w-16 text-[#888]">commandes</span>
                    </div>

                </div>
            </div>
            <p class="text-xl font-semibold ml-4 mt-2 lg:text-center">{{ boutique.name }}</p>
            <div>
                <div class="flex flex-col mt-4 ml-2 lg:flex-row lg:justify-evenly lg:mt-8">
                    <span class="text-primary flex items-center gap-2">
                        <Icon :name="'phone'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.telephone }}
                    </span>
                    <span class="text-primary flex items-center gap-2">
                        <Icon :name="'address'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.address }}
                    </span>
                    <span class="text-primary flex items-center gap-2">
                        <Icon :name="'mail'" width="16" height="16" fill="#7e4a7c"></Icon>{{ boutique.email }}
                    </span>
                </div>
                <div class="m-2 duration-300 overflow-hidden" :class="openDesc ? 'max-h-auto' : 'max-h-12'">
                    <p @click="openDesc = !openDesc">{{ boutique.description }}</p>
                    <!-- <button class="float-right mt-[-22px] mr-2" @click="openDesc = true">Voir plus</button> -->
                </div>
                <!-- <div class="flex gap-2 mt-4 px-4">
                    <Link href="/article/create" class="w-full bg-primary text-center px-2 py-1 rounded-[8px] text-white">
                    Envoyer un méssage</Link>
                </div> -->
            </div>
            <div class="mt-4">
                <div class="flex justify-center mb-4">
                    <span class="text-xl mr-2" :class="display === 'plusRecent' ? 'font-semibold' : ''">Les plus récents</span>
                </div>
                <div v-if="articles.length > 0">
                    <div class="gap-1 mx-1 grid grid-rows-3 grid-cols-3 overflow-scroll h-[490px] lg:h-auto">

                    <div v-for="art,i in articles" :key="i" class="h-40 lg:h-auto overflow-hidden relative">
                        <img class="h-full w-full" :src="getPath('article', art.article_media.path)" alt="">
                        <span class="absolute top-0 right-0 m-1 rounded-full h-8 bg-white min-w-8 flex justify-center text-primary items-center font-bold text-xl" v-if="display === 'tendance'"> {{ art.commande }}</span>
                        <Link class="absolute top-0 left-0 right-0 bottom-0 opacity-0" :href="'/view/article/' + art.id"></Link>
                    </div>
                </div>
                <PrimaryButton @click="showMore()">Voir plus</PrimaryButton>
            </div>
        </div>
    </div>
</AuthenticatedLayout></template>