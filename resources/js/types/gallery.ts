export type GalleryImage = {
    id: number;
    filename: string;
    original_name: string;
    mime_type: string | null;
    size: number | null;
    width: number | null;
    height: number | null;
    url: string;
    created_at?: string | null;
};

export type GalleryFolderSummary = {
    id: number;
    title: string;
    description: string | null;
    created_at: string | null;
    images_count: number;
    cover_image_url: string | null;
};

export type GalleryFolderDetail = {
    id: number;
    title: string;
    description: string | null;
    images: GalleryImage[];
};
