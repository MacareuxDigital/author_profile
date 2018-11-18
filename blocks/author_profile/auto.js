var author_profile ={
    init:function(){
        this.showHideDisplayType();

        $('#displayMode').change(function() {
            author_profile.showHideDisplayType();
        });

    },

    showHideDisplayType: function() {
        if($('#displayMode').val() === 'E') {
            $('.ccm-block-author-profile-list-option').show();
        } else {
            $('.ccm-block-author-profile-list-option').hide();
        }
    }
};