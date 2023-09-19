<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app">
    <v-app>
        <v-main>
            <v-container>
                <v-app-bar
                    color="purple"
                    elevation="4"
                    class="white--text mb-8 rounded"
                >
                    <v-app-bar-title>
                        Find.io
                    </v-app-bar-title>
                </v-app-bar>
                {{--                <div class="flex">--}}
                <v-text-field
                    class="mb-12"
                    color="purple"
                    v-model="searchRequest"
                >
                    <template v-slot:prepend-inner>
                        <v-icon>mdi-magnify</v-icon>
                    </template>
                </v-text-field>
                {{--                <v-text-field--}}
                {{--                        class="mb-12 w-25"--}}
                {{--                        color="purple"--}}
                {{--                        type="number"--}}
                {{--                        v-model="resultsCount"--}}
                {{--                    >--}}
                {{--                    </v-text-field>--}}
                {{--                </div>--}}
                <v-row dense>
                    <v-col
                        v-for="product in products"
                        :key="product.id"
                        class="d-flex"
                        style="flex-direction: column; display:flex;"
                        cols="12"
                        sm="6"
                        md="3"
                    >
                        <v-card class="purple--text flex-grow-1">
                            <v-card-title class="d-inline-flex">
                                @{{ product.title }}
                            </v-card-title>
                            <v-card-subtitle class="mt-0">
                                @{{ product.description }}
                            </v-card-subtitle>
                            <v-card-text>
                                <v-img :src="product.photo"></v-img>
                            </v-card-text>
                            <v-card-actions class="pl-4 pb-4 pt-0">
                                <v-btn outlined color="purple" @click.prevent="">
                                    More
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script>
    new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            searchRequest: '',
            resultsCount: null,
            products: [
                // {
                //     id: 1,
                //     title: "Card 1",
                //     description: "Description for Card 1",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 2,
                //     title: "Card 2",
                //     description: "Description for Card 2",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 3,
                //     title: "Card 3",
                //     description: "Description for Card 3",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 4,
                //     title: "Card 4",
                //     description: "Description for Card 4",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 5,
                //     title: "Card 5",
                //     description: "Description for Card 5",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 6,
                //     title: "Card 6",
                //     description: "Description for Card 6",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 7,
                //     title: "Card 7",
                //     description: "Description for Card 7",
                //     photo: "/images/card.png",
                // },
                // {
                //     id: 8,
                //     title: "Card 8",
                //     description: "Description for Card 8",
                //     photo: "/images/card.png",
                // },
            ],
        },
        methods: {
            getProducts: async function () {
                this.showLoader = true

                let search = this.searchRequest ? 'search=' + this.searchRequest : '';
                let count = this.resultsCount ? 'count=' + this.resultsCount : '';

                let queryString = this.searchRequest || this.resultsCount ? '?' + [search, count].join('&') : '';

                let {data} = await axios.get('/api/elasticsearch/recommendations' + queryString, {
                    headers: {}
                })

                let products = []

                console.log(data)

                data.forEach(function (product) {
                    products.push({
                        id: product._id,
                        description: product._source.description
                    })
                })

                this.products = products

                this.showLoader = false

            }
        },
        mounted: async function () {
            await this.getProducts()
        },
    })

    axios.interceptors.response.use(
        function (response) {
            return response
        },
        function (error) {
            alert('Error occured:' + error.response.data.data)

            return new Promise(() => {
            });
        }
    );
</script>
</body>
</html>
