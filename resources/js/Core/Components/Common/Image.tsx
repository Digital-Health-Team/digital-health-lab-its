import React, { useState, useEffect } from 'react';
import { cn } from '@/Core/Utils/utils';

// ============================================
// IMAGE COMPONENT WITH OPTIMIZATION
// ============================================

type ImageFit = 'cover' | 'contain' | 'fill' | 'none' | 'scale-down';
type ImagePriority = 'eager' | 'lazy';

interface ImageOwnProps {
  src: string;
  alt: string;
  width?: number;
  height?: number;
  className?: string;
  objectFit?: ImageFit;
  priority?: ImagePriority;
  quality?: number;
  placeholder?: 'blur' | 'empty';
  blurDataURL?: string;
  onLoad?: () => void;
  onError?: () => void;
  sizes?: string;
  fallback?: string;
}

type ImageProps = ImageOwnProps &
  Omit<React.ComponentPropsWithRef<'img'>, keyof ImageOwnProps>;

const Image = React.forwardRef<HTMLImageElement, ImageProps>(
  (
    {
      src,
      alt,
      width,
      height,
      className,
      objectFit,
      priority = 'lazy',
      quality = 75,
      placeholder = 'empty',
      blurDataURL,
      onLoad,
      onError,
      sizes,
      fallback = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f3f4f6" width="400" height="300"/%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" fill="%239ca3af" font-family="sans-serif" font-size="18"%3EImage%3C/text%3E%3C/svg%3E',
      ...restProps
    },
    ref,
  ) => {
    const [isLoading, setIsLoading] = useState(true);
    const [isError, setIsError] = useState(false);
    const [currentSrc, setCurrentSrc] = useState<string>(
      placeholder === 'blur' && blurDataURL ? blurDataURL : fallback,
    );

    useEffect(() => {
      // Preload image
      const img = new window.Image();
      img.src = src;

      img.onload = () => {
        setCurrentSrc(src);
        setIsLoading(false);
        onLoad?.();
      };

      img.onerror = () => {
        setIsError(true);
        setIsLoading(false);
        setCurrentSrc(fallback);
        onError?.();
      };

      return () => {
        img.onload = null;
        img.onerror = null;
      };
    }, [src, onLoad, onError, fallback]);

    // Generate srcset for responsive images
    const generateSrcSet = () => {
      if (!width) {
        return undefined;
      }

      const breakpoints = [640, 750, 828, 1080, 1200, 1920, 2048, 3840];
      const validBreakpoints = breakpoints.filter(
        (bp) => bp <= (width || 0) * 2,
      );

      return validBreakpoints
        .map((bp) => `${src}?w=${bp}&q=${quality} ${bp}w`)
        .join(', ');
    };

    // Object fit mapping
    const objectFitClass = {
      cover: 'object-cover',
      contain: 'object-contain',
      fill: 'object-fill',
      none: 'object-none',
      'scale-down': 'object-scale-down',
    };

    return (
      <div
        className={cn('relative overflow-hidden', className)}
        style={{
          width: width ? `${width}px` : undefined,
          height: height ? `${height}px` : undefined,
        }}
      >
        <img
          ref={ref}
          src={currentSrc}
          alt={alt}
          width={width}
          height={height}
          loading={priority === 'eager' ? 'eager' : 'lazy'}
          decoding={priority === 'eager' ? 'sync' : 'async'}
          srcSet={!isError && !isLoading ? generateSrcSet() : undefined}
          sizes={sizes}
          className={cn(
            'h-full w-full transition-opacity duration-300',
            objectFit && objectFitClass[objectFit],
            isLoading && placeholder === 'blur'
              ? 'scale-110 blur-sm'
              : 'blur-0 scale-100',
            isLoading ? 'opacity-0' : 'opacity-100',
          )}
          {...restProps}
        />

        {/* Loading skeleton */}
        {isLoading && placeholder === 'empty' && (
          <div className="absolute inset-0 animate-pulse bg-gray-200" />
        )}
      </div>
    );
  },
);

Image.displayName = 'Image';

export { Image };
export type { ImageProps, ImageFit, ImagePriority };
