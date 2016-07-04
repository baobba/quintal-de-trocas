class ToyCategoriesController < InheritedResources::Base

  private

    def toy_category_params
      params.require(:toy_category).permit(:title)
    end
end

