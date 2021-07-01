<!-- =========================================================================================
    File Name: TheFooter.vue
    Description: Footer component
    Component Name: TheFooter
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template functional>
    <!-- <footer class="the-footer flex-wrap justify-between" :class="classes">
        <span>COPYRIGHT &copy; {{ new Date().getFullYear() }} <a href="https://1.envato.market/vuexy_admin" target="_blank" rel="nofollow">Pixinvent</a>, All rights Reserved</span>
        <span class="md:flex hidden items-center">
            <span>Hand-crafted & Made with</span>
            <feather-icon icon="HeartIcon" svgClasses="stroke-current text-danger w-6 h-6" class="ml-2" />
        </span>
    </footer>  -->

    <footer :class="classes">
        <!-- <div class="vx-row" style="backgroundColor:green">
            <div class="vx-col w-1/3 " >
              <div class="align">
                Migas
              </div>
            </div>
            <div class="vx-col w-1/3 ">
              <div class="align">
                Batubara
              </div>
            </div>
            <div class="vx-col w-1/3 ">
              <div class="align">
                Panas Bumi
              </div>
            </div>
        </div>  -->

        <!-- <span>COPYRIGHT &copy; {{ new Date().getFullYear() }} <a href="https://1.envato.market/vuexy_admin" target="_blank" rel="nofollow">Pixinvent</a>, All rights Reserved</span>
        <span class="md:flex hidden items-center">
            <span>Hand-crafted & Made with</span>
            <feather-icon icon="HeartIcon" svgClasses="stroke-current text-danger w-6 h-6" class="ml-2" />
        </span> -->

        <!-- Begin Mailchimp Signup Form -->

        <footer>
            <!-- <footer style="width:100%"> -->
            <div class="subscribe-content">
                <!-- <vs-row vs-type="flex" vs-justify="flex-end"> -->
                <vs-row vs-w="12">
                    <vs-col
                        vs-type="flex"
                        vs-justify="center"
                        vs-align="center"
                        vs-lg="12"
                        vs-sm="12"
                        vs-xs="12"
                        class="mb-5"
                    >
                        <img
                            class="supportByImage"
                            src="/images/supportby2.png"
                        />
                    </vs-col>
                </vs-row>
                <vs-row vs-w="12">
                    <vs-col
                        vs-type="flex"
                        vs-justify="center"
                        vs-align="center"
                        vs-lg="12"
                        vs-sm="12"
                        vs-xs="12"
                        class="mt-5 mb-5"
                    >
                        <span>&copy; 2020-2021</span>
                    </vs-col>
                    <!-- <vs-col class="rightBottom" vs-type="flex" vs-justify="flex-end" vs-align="flex-end" vs-w="12">
                        <img class="supportByImage" src="/images/allLogo5.png"   />
                    </vs-col> -->
                </vs-row>
            </div>
        </footer>

        <!-- <div class="subscribe-content" id="mc_embed_signup">

        <vs-row
            style="marginBottom:15px"
            vs-align="flex-start"
            vs-type="flex" vs-justify="center" vs-w="12">
            <vs-col  vs-type="flex" vs-justify="center" vs-align="center" vs-w="4">
                <img  src="/images/ugmLogo.png"   />
        </vs-col>
        </vs-row>
        <vs-row vs-justify="center" style="marginBottom:10px">
        <h3>
            <b>
                Supported By :
            </b>
            </h3>
        </vs-row>
        <vs-row

        vs-align="flex-start"
        vs-type="flex" vs-justify="center" vs-w="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
            <img class="supportByImage" src="/images/kemendikbud2.png"  />
        </vs-col>
        <vs-col  vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
            <img class="supportByImage" src="/images/ristekbrin1.png"  />
        </vs-col>
        <vs-col  vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
            <img class="supportByImage" src="/images/lpdp1.png"  />
        </vs-col>
        </vs-row>

    </div> -->
    </footer>
</template>

<script>
export default {
    name: "the-footer",
    props: {
        classes: {
            type: String
        }
    }
};
</script>

<style>
.align {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40px;
    background: #dce1f5;
    border-radius: 5px;
    font-family: Tahoma;
    font-weight: bold;
    color: #4040a5;
}

.padding-cat {
    padding: 0 5rem !important;
}
</style>

<style lang="stylus" scoped>
getColor(colorx, alpha = 1)
    unquote("rgba(var(--vs-"+colorx+"), "+alpha+")")
getVar(var)
    unquote("var(--vs-"+var+")")
.darken
  .subscribe-content
    #mc_embed_signup_scroll
      input
        &[type="email"]
          background getVar(theme-bg) !important

.subscribe-content
  width 100%
  height 18vh
  overflow hidden
  padding-right 40px
  padding-top 35px
  padding-left 40px
  display flex
  align-items center
  justify-content center
  flex-direction column
  .con-form
    width 400px
  h4
    padding 0px
    margin 0px
    padding-bottom 15px
    text-align left
    padding-left 10px
    >>>b
      color getColor('primary',1) !important



.footer
  width 100%
  margin 0px
  display flex
  align-items center
  justify-content center
  flex-direction column
  background-color #DCDCDC
  border-radius 0px 0px 0px 0px
  .footer__uls
    display flex
    align-items flex-start
    justify-content center
  .copy
    position relative
    bottom 0px
    width 100%
    max-width 1000px
    display flex
    align-items center
    justify-content space-between
    font-size .8rem
    padding 20px
    .span-copy
      opacity .5
    .span-lusaxweb
      span
        opacity .5
      a
        opacity .8
        &:hover
          opacity 1
  ul
    padding 30px
    list-style none
    margin 0px
    margin 30px 20px
    .title
      margin-bottom 15px
    li
      font-size 1.1rem
      font-weight bold
      opacity .8
      margin 10px 0px
      a
        font-size .85rem
        opacity .5
        transition all .25s ease
        font-weight normal
        padding 5px
        display block
        &:hover
          opacity 1
          color getColor('primary') !important
@media (max-width: 1200px)
  .footer
    ul
      margin 30px 10px
      padding 15px
    .supportByImage
      width 100%
      padding-right 20px
    .rightBottom
      padding-right 20px
  .subscribe-content
    padding-top 35px
    height:19vh
@media (max-width: 800px)
  .footer
    .footer__uls
      flex-wrap wrap
    ul
      margin 30px 10px
      padding 15px
      width calc(50% - 20px)
    .supportByImage
      width 100%
      padding-right 20px
    .rightBottom
      padding-right 20px

@media (max-width: 600px)
  .footer
    padding-bottom 100px !important
    .supportByImage
      width 100%
      padding-right 20px
    .rightBottom
      padding-right 20px

@media (max-width: 500px)
  .footer
    p
    .subscribe-content
      padding 40px 10px
      .con-form
        width 100%
        input[type="email"]
          width calc(100% - 50px) !important
@media (max-width: 400px)
  .footer
    .subscribe-content
      padding 40px 20px
      #mc-embedded-subscribe-form
        display block
        width 100%
      #mc_embed_signup_scroll
        display block
        width 100%
      .con-form
        input[type="email"]
          width 100% !important
          margin-bottom 10px !important
      input
        width 100% !important
        margin-right 0px
        margin-top 10px
        margin-left 0px !important
    ul
      padding  0px 30px
      width calc(100% - 20px)
  .supportByImage
    width 100%
    padding-right 5px
  .rightBottom
    padding-right 5px
</style>
