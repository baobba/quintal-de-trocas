class ItemImagesController < InheritedResources::Base

  private

    def item_image_params
      params.require(:item_image).permit(:item_id, :image, :featured)
    end
end

