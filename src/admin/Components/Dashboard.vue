<template>
  <div>
    <div class="col-12 md:col-6 p-6 text-center md:text-left flex align-items-center ">
      <section>
        <span class="block text-6xl font-bold mb-1">Dashboard Page Vue + Laravel Mix</span>
        <!-- a smalll list of features likes tags styled -->
        <div class="p-4">
          <ul class="flex space-x-4 list-none p-0 text-blue-600">
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Vue
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Tailwind
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Laravel Mix
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Element +
            </li>
          </ul>
        </div>

        <div class="text-2xl text-primary font-bold mb-3">Hello, There</div>
        <p class="mt-0 mb-4 text-700 line-height-3">No need to reload your page 🥳</p>
        <!-- resonse from server highlighed-->
         <pre class=" p-3 mb-0 text-center" style="text-align:center;background-color: #f8f9fa; border-radius: 4px;">
          {{ data }}
        </pre>
      </section>
    </div>
    
  </div>
</template>
<script type="module">

export default {
    name: "dashboard-page",
    data() {
      return {
        data: null,
      };
    },
    components: {
    },
    mounted() {
      console.log("Dashboard Page mounted.");
      this.$get('test')
        .then(response => {
          this.data = response.data;

        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });

  }
};
</script>
