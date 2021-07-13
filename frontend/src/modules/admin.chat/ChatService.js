export default class ChatService {
  constructor(Restangular, $q, EditableMap) {
    this.Restangular = Restangular;
    this.EditableMap = EditableMap;
    this.$q = $q;
    this.conversations = null;
    this.messages = null;
    this.mess = null;
  }

  getCustomerConversation() {
    let self = this;
    let dfd = self.$q.defer();

    self.Restangular.one("chat")
      .one("conversation")
      .get()
      .then(
        (res) => {
          self.conversations = self._toObject(res.conversations);
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
          self.messages = self._toObject(res.messages);
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
   
   /**
     * Calls for post image to chat
     *
     * @method postImage
     * @param {Mess} newMess
     * @param {Object} data
     * @returns {Promise}
     */
    postImage(newMess) {
      let fd = new FormData();

      fd.append('message[conversationId]', newMess.conversationId);
      // fd.append('message[conversationParticipantIds][0]', newMess.senderId);
      // fd.append('message[conversationParticipantIds][1]', newMess.customerId);
      fd.append('message[senderId]', newMess.senderId);
      fd.append('message[senderName]', newMess.senderName);
      fd.append('message[message]', "photo");
      fd.append('message[messageTimestamp]',newMess.messageTimestamp);
      fd.append('message[photoMessage]', newMess.file);

      return this.Restangular
          .one('chat').one('message')
          .one('photo')
          .withHttpConfig({transformRequest: angular.identity})
          .customPOST(fd, '', undefined, {'Content-Type': undefined});
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
