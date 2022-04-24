export default {
    props: {
        image: String,
        userName: String,
        surname: String,
        lastActivity: String,
    },
    template: `
    <div class="chat-header clearfix">
        <div class="row">
            <div class="col-lg-6">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                    <img :src="image" alt="avatar">
                </a>
                <div class="chat-about">
                    <h6 class="m-b-0">{{ userName }} {{ surname }}</h6>
                    <small>{{ lastActivity }}</small>
                </div>
            </div>
        </div>
    </div>
  `,
};
