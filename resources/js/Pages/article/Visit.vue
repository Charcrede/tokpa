<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Icon from "@/Icons/Icon.vue";
import { Link } from "@inertiajs/vue3";
export default {
    props: {
        article: Object,
        image: Object,
        proprio: Boolean,
        images: Array,
        articles: Array,

    },
    components: {
        AuthenticatedLayout,
        Icon,
        Link,
    },
    data() {
        return {
            dotTable: [],
            current: 0,
            intervalId: '',
            form: {
                name: '',
                description: '',
                price: '',
                category: '',
                image: '',
                images: [],
            },
            preview: [],
            previews: [],
            modifier: true,
        }
    },
    mounted() {
        for (let i = 0; i < this.images.length + 1; i++) {
            this.dotTable[i] = i;
        }
        clearInterval(this.intervalId);
        this.intervalId = setInterval(() => {
            this.current < this.images.length ? this.current++ : this.current = 0
            let div = document.getElementById('showArticlesVisit');
            if (div) {
                div.scrollLeft = div.offsetWidth * this.current
            } else {
                clearInterval(this.intervalId);
            }
        }, 4000);
    },
    methods: {
        getPath(name) {
            return new URL("../../../../public/article_medias/" + name, import.meta.url).href
        },
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="relative h-full">
            <article class="bg-white p-2 rounded-3xl mx-4 lg:w-1/3 lg:m-auto">
                <div class="relative flex flex-col items-center">
                    <div class="flex overflow-scroll max-h-[500px] scroll-smooth" id="showArticlesVisit">
                        <img :src="getPath(image.path)" alt="" class="rounded-3xl">
                        <img v-for="img, i in images" :key="i" :src="getPath(img.path)" alt="" class="rounded-3xl">
                        <Link :href="'/visit/shop/' + article.boutique.name"
                            class="absolute top-0 right-0 font-bold px-2 h-8 flex justify-center items-center mr-2 mt-2 rounded-full bg-white">
                        De chez : {{ article.boutique.name }}</Link>
                    </div>
                    <div class="flex gap-1 relative bottom-4">
                        <span v-for="dot in dotTable" :class="dot == current ? 'bg-blackTrans' : 'bg-whiteTrans'"
                            class="w-2 h-2 block rounded-full"></span>
                    </div>
                </div>
                <p class="font-bold text-2xl font-economica text-center w-fit mx-auto border-b border-b-primary text-primary px-2 m-2">
                    {{ article.name }}</p>
                <p class="font-semibold m-2">{{ article.description }}</p>
                <div class="flex justify-between m-2">
                    <span class="text-[#505050]">{{ article.category }}</span>
                    <span class="text-primary">{{ article.price }} F</span>
                </div>
            </article>

            <section>
                <h3 class="font-bold text-center text-2xl mt-4">Voir aussi : </h3>
                <div v-if="articles.length == 0" class="text-xl mt-8 text-center">
                    Oups ! Aucun autre article de cette catégorie n'est disponible
                </div>
                <div class="flex mt-4" v-else>
                    <article v-for="art, i in articles" :key="i"
                        class="p-2 bg-white rounded-2xl border relative w-1/2 lg:w-1/3 border-primary shadow-primary shadow m-2">
                        <Link class="absolute top-0 bottom-0 right-0 left-0 opacity-0" :href="'/view/article/' + art.id">
                        </Link>
                        <h3 class="text-center text-xl font-semibold text-primary">{{ art.name }}</h3>
                        <img class="rounded-2xl" :src="getPath(art.article_media.path)" alt="">
                        <div class="flex justify-between items-center">
                            <span>{{ art.price }} F</span>
                            <Link :href="'/visit/shop/' + art.boutique.name"
                                class="font-italic border-b border-b-primary text-primary">{{ art.boutique.name }}
                            </Link>
                    </div>
                </article>
            </div>
        </section>
    </div>
</AuthenticatedLayout></template>