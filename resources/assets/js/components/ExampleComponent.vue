<template>
    <div></div>
</template>
<script>
export default {
    mounted() {
        axios.get('/api/user').then(response => {
            console.log('Success');
            console.log(response.data);
        }).catch(error => {
            console.log('Error');
            console.log(error);
        });
    }
}
</script>