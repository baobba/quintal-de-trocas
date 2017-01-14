class ItemAgesController < InheritedResources::Base

  private

    def item_age_params
      params.require(:item_age).permit(:title)
    end
end

