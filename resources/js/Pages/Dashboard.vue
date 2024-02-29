<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
export default {
    props: {
        articles: Array,
    },
    data() {
        return {
            isShowed: "yours",
            showedArticles: [],
            loading: false,
        }
    },
    mounted() {
        this.showedArticles = this.articles;
        // window.addEventListener('scroll', this.handleScroll());
    },
    components: {
        AuthenticatedLayout,
        Head,
        Link
    },
    created() {
        // this.loadItems();
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        getPath(name) {
            return new URL("../../../../public/article_medias/" + name, import.meta.url).href
        },
        changeShowed(str) {
            if (str == 'all') {
                axios.get('/allArticles').then((resp) => {
                    this.showedArticles = resp.data;
                    this.isShowed = 'all'
                })
            } else {
                axios.get('/myArticles').then((resp) => {
                    this.showedArticles = resp.data;
                    this.isShowed = 'yours'
                })
            }
        },
        handleScroll() {
            if (((window.innerHeight + window.scrollY) >= document.body.scrollHeight) && !this.loading) {
                this.loading = true;
                if (this.isShowed == 'all') {
                    axios.get(`/moreD/${this.showedArticles.length + 10}`).then((resp) => {
                        this.showedArticles = resp.data;
                        this.loading = false;
                    })
                } else {
                    axios.get(`/moreA/${this.showedArticles.length + 10}`).then((resp) => {
                        this.showedArticles = resp.data;
                        this.loading = false;
                    })
                }
            }
        },
        command(art){
            axios.post('/article/commande', {'id' : art.id}).then(() => {
                window.location.href='https://wa.me/+229' + art.boutique.telephone + '?text=' + 'Salut, j\'ai vue votre article ' + art.name + ' sur Tokpa.shop, et il a suscité mon intérêt'
            })
        }
    },
    watch: {
        window() {
            this.handleScroll();
        }
    }

}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="text-center">
            <button class="ml-2 text-xl" :class="isShowed == 'yours' ? 'font-bold' : ''"
                @click="changeShowed('yours')">Pour vous</button>
            <span class="text-3xl font-bold px-2">|</span>
            <button class="ml-2 text-xl" :class="isShowed == 'all' ? 'font-bold' : ''"
                @click="changeShowed('all')">Plus populaires</button>
        </div>
        <div class="lg:flex lg:gap-4 lg:grid lg:grid-cols-3">
            <article v-for="art, i in showedArticles" :key="i"
                class="bg-white p-2 shadow m-2 lg:rounded-2xl">
                <h3 class="text-center text-2xl font-bold text-primary">{{ art.name }}</h3>
                <p class="my-4">{{ art.description }}
                </p>
                <div class="relative">
                    <Link v-if="art.boutique.user_id != $page.props.auth.user.id"
                        class="absolute top-0 bottom-0 right-0 left-0 opacity-0" :href="'/view/article/' + art.id">
                    </Link>
                    <img class="rounded-2xl" :src="getPath(art.article_media.path)" alt="">
                </div>
                <div class="flex justify-between items-center mt-4">
                    <!-- <a v-if="art.boutique.user_id != $page.props.auth.user.id"
                        :href="'https://wa.me/+229' + art.boutique.telephone + '?text=' + 'Salut, j\'ai vue votre article ' + art.name + ' sur Tokpa.shop, et il a suscité mon intérêt'"
                        class="mt-4 rounded-2xl bg-primary text-white px-4 py-2">Commander</a> -->
                        <button @click="command(art)">Commander</button>
                    <span>{{ art.price }} F</span>
                    <Link :href="'/visit/shop/' + art.boutique.name"
                        class="font-italic border-b border-b-primary text-primary">{{ art.boutique.name }}</Link>
                </div>
            </article>
        </div>
        <div class="h-8 flex items-center justify-center rounded-xl mx-2" :class="loading ? 'bg-whiteTrans' : 'bg-transparent'">
            <div v-if="loading" class="w-6 h-6 animate-spin rounded-full border-2 border-[#999] border-b-black">

            </div>
        </div>
    </AuthenticatedLayout>
</template>
