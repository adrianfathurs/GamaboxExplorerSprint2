export default ({
    namespaced: true,
    state: {
        dataMap: [],
    },
    getters: {
        getDataMap(state) {

            return state.dataMap;
        }
    },
    mutations: {
        mSavedDataMap(state, payload) {
            var data = state.dataMap;
            console.log(data.length);
            if (data.length <= 2) {
                try {
                    function getItem(item) {
                        return item.filenamed == payload.filenamed;

                    }
                    var resp = data.find(getItem)
                    if (!resp) {
                        console.log("tidak ditemukan file yang sama");
                        console.log(state.dataMap);
                        return state.dataMap.push(payload);

                    }

                } catch (error) {
                    console.log("terjadi error Program");
                }
            } else {
                try {
                    function getItem(item) {
                        return item.filenamed == payload.filenamed;

                    }
                    var resp = data.find(getItem)
                    if (!resp) {
                        state.dataMap.splice(0, 1);
                        state.dataMap.push(payload);
                    }
                } catch (error) {
                    console.log("lebih dari 3 terjadi error program");
                }
            }
        }
    },
    actions: {
        saveDataMap(context, payload) {
            console.log("ini ada dia actions")
            console.log(payload);
            context.commit("mSavedDataMap", payload)
        }
    }

})
