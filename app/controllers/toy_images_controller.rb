class ToyImagesController < InheritedResources::Base

  private

    def toy_image_params
      params.require(:toy_image).permit(:toy_id, :image, :featured)
    end
end

