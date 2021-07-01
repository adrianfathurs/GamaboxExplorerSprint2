<template>
    <div class="tengahin">
        <div text-center>
            <p>
                CDMS bisa di akses pada :
                <!-- <button @click="redirectCDMS()">Redirect To CDMS</button> -->
                <vs-button @click="redirectCDMS()" color="danger" type="flat"
                    >CDMS Services</vs-button
                >
            </p>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        redirectCDMS() {
            // Rubah linknya di sini
            window.open("https://cdms.widyaanalytic.com/", "_blank");
        }
    },
    // mounted(){

    //         console.log(this.$store.state.AppActiveUser, 'wkwkwk')
    //         return this.$store.state.AppActiveUser;

    // }
};
</script>
<style>
.tengahin {
    display: flex;
    justify-content: center;
}
</style>
