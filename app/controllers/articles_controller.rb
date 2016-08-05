class ArticlesController < InheritedResources::Base

  def index
    @articles = Article.order("id DESC").page params[:page]
  end

  def show
    @article = Article.find(params[:id])
    @latest = Article.order("id desc").limit(3)
    @latest_category = Article.where(["category = ?", @article.category]).order("id desc").limit(3)
  end

  private

    def article_params
      params.require(:article).permit(:title, :body, :category, :published_at, :cover, :active, :user_id)
    end
end

