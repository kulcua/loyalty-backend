export default class ChatService {
  constructor(Restangular, $q, EditableMap) {
    this.Restangular = Restangular;
    this.EditableMap = EditableMap;
    this.$q = $q;
    this.conversations = null;
    this.messages = null;
  }

  getCustomerConversation() {
    let self = this;
    let dfd = self.$q.defer();

    self.Restangular.one("chat")
      .one("conversation")
      .get()
      .then(
        (res) => {
          // self.conversations = res.conversations;
          self.conversations = self._toObject(res.conversations);
          // $log.info(self.conversations);
          dfd.resolve();
        },
        () => {
          dfd.reject();
        }
      );
    return dfd.promise;
  }

  getConversations() {
    return this.conversations;
  }

  getMessages(conversationId) {
    let self = this;
    let dfd = self.$q.defer();

    self.Restangular.all("chat")
      .customGET("message", {
        conversationId: conversationId,
      })
      .then(
        (res) => {
          // self.conversations = res.conversations;
          self.messages = self._toObject(res.messages);
          // $log.info(self.conversations);
          dfd.resolve();
        },
        () => {
          dfd.reject();
        }
      );
    return dfd.promise;
  }

  postChat(newMess) {
    return this.Restangular.one("chat").one("message").customPOST({
      message: newMess,
    });
  }

  putConversation(conversationId, editedConversation) {
    let self = this;

    return self.Restangular.one("chat")
      .one("conversation", conversationId)
      .customPUT({
        conversation: self.Restangular.stripRestangular(
          self.EditableMap.conversation(editedConversation)
        ),
      });
  }

  _toObject(data) {
    let res = {};
    for (let i in data) {
      res[i] = data[i];
    }

    return res;
  }
}

ChatService.$inject = ["Restangular", "$q", "EditableMap"];
