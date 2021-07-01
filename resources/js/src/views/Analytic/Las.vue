<template>
    <div>
        <form action="http://localhost:3000/upload-file" target="my-iframe" method="post" enctype="multipart/form-data">
            <div>
                <div class="custom-file">
                    <input type="file" name="filename" class="inputfile">
                    <!-- <label class="custom-file-label" for="inputGroupFile01">Choose file</label> -->
                </div>
            </div> <br>
            <input type="submit" value="upload" name="submit" class="btn btn-primary">
        </form>
        <iframe src="http://localhost:3000/upload-file" name="my-iframe" class="frameLas" frameborder="0">
        </iframe>
    </div>
</template>

<script>
export default {
  data() {
    return {
        
    }
  },
  components: {
      
  },
  watch: {
    
  },
  computed: {
  },
  methods: {
        // lasFile() {
        //     // As with JSON, use the Fetch API & ES6
        //     // fetch('./File/las_file.las')
        //     // .then(response => response.text())
        //     // .then(data => {
        //     //     // Do something with your data
        //     //     console.log(data);
        //     // });
        // }      
  },
  mounted() {      
        let cek = this.URINode
  }
}
</script>

<style lang="scss">
.frameLas {
    height: 100vh;
    width: 100%;
}
</style>