export default class SuggestionBoxService {
    constructor(Restangular, EditableMap) {
        this.Restangular = Restangular;
        this.EditableMap = EditableMap;
    }

    getSuggestionBoxs(params) {
        return this.Restangular.all('suggestion_box').one('suggestion_box').getList(params);
    }

    // getLevel(levelId) {
    //     return this.Restangular.one('level', levelId).get();
    // }

    // postLevel(newLevel) {
    //     return this.Restangular.one('level').one('create').customPOST({level: newLevel})
    // }

    // getLevelCustomers(params, levelId) {
    //     if(!params) {
    //         params = {}
    //     }
    //     return this.Restangular.one('level', levelId).all('customers').getList();
    // }
    // getFile(levelId) {
    //     return this.Restangular.one('csv').one('level', levelId).get();
    // }
    // putLevel(editedLevel) {
    //     let self = this;

    //     return editedLevel.customPUT({level: self.EditableMap.level(editedLevel)});
    // }

    // postActivateLevel(state, levelId) {
    //     let self = this;

    //     return this.Restangular.one('level').one(levelId).one('activate').customPOST({active: state})
    // }
}

SuggestionBoxService.$inject = ['Restangular', 'EditableMap'];
