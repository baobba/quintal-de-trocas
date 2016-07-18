class ArticlesController < InheritedResources::Base

  def index
    @articles = Article.all.page params[:page]
  end

  private

    def article_params
      params.require(:article).permit(:title, :body, :category, :user_id)
    end
end

