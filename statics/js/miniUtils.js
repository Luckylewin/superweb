/**
 * Created by sz on 2018/4/17.
 */
Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};

Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};

var miniUtils = {
    'in_array' : function (search, array){
        for(var i in array){
            if(array[i] == search){
                return true;
            }
        }
        return false;
    }
};