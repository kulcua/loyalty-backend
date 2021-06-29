export default class SuggestionBoxService {
    constructor(Restangular, EditableMap) {
        this.Restangular = Restangular;
        this.EditableMap = EditableMap;
    }

    getSuggestionBoxs(params) {
        return this.Restangular.all('suggestion_box').getList(params);
    }

    getSuggestionBox(suggestionBox) {
        return this.Restangular.one('suggestion_box', suggestionBox).get();
    }

    // postActivateLevel(state, levelId) {
    //     let self = this;

    //     return this.Restangular.one('level').one(levelId).one('activate').customPOST({active: state})
    // }

    /**
     * Calls for suggestionbox images
     *
     * @method getSuggestionBoxImages
     * @param {Integer} suggestionboxId
     * @returns {Promise}
     */
     getSuggestionBoxImage(suggestionboxId) {
        return this.Restangular
            .one('suggestion_box')
            .one('photo', suggestionboxId)
            .get()
    }
}

SuggestionBoxService.$inject = ['Restangular', 'EditableMap'];
