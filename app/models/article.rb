class Article < ActiveRecord::Base
  belongs_to :user
  validates :title, :body, presence: true

  mount_uploader :cover, ArticleUploader

  def to_param
    [id, title.parameterize].join("-")
  end
end
