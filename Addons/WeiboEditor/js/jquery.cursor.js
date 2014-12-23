jQuery.fn.extend({  
    getCurPos: function(){  
        var e=$(this).get(0);  
        e.focus();  
        if(e.selectionStart){    //FF  
            return e.selectionStart;  
        }  
        if(document.selection){    //IE  
            var r = document.selection.createRange();  
            if (r == null) {  
                return e.value.length;  
            }  
            var re = e.createTextRange();  
            var rc = re.duplicate();  
            re.moveToBookmark(r.getBookmark());  
            rc.setEndPoint('EndToStart', re);  
            return rc.text.length;  
        }  
        return e.value.length;  
    },  
    setCurPos: function(pos) {  
        var e=$(this).get(0);  
        e.focus();  
        if (e.setSelectionRange) {  
            e.setSelectionRange(pos, pos);  
        } else if (e.createTextRange) {  
            var range = e.createTextRange();  
            range.collapse(true);  
            range.moveEnd('character', pos);  
            range.moveStart('character', pos);  
            range.select();  
        }  
    },
	insertAtCursor : function(myValue) {
    var $t = $(this)[0];
    if (document.selection) {
        this.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
        this.focus();
    } else if ($t.selectionStart || $t.selectionStart == '0') {
        var startPos = $t.selectionStart;
        var endPos = $t.selectionEnd;
        var scrollTop = $t.scrollTop;
        $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
        this.focus();
        $t.selectionStart = startPos + myValue.length;
        $t.selectionEnd = startPos + myValue.length;
        $t.scrollTop = scrollTop;
        } else {
            this.value += myValue;
            this.focus();
        }
    }
});