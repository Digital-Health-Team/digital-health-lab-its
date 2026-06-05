import React from 'react';

// ============================================
// CONTAINER COMPONENT (div, section, etc)
// ============================================

type ContainerOwnProps<T extends React.ElementType> = {
  as?: T;
  children?: React.ReactNode;
  className?: string;
  maxWidth?:
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'
    | '2xl'
    | '3xl'
    | '4xl'
    | '5xl'
    | '6xl'
    | '7xl'
    | 'full';
  centerContent?: boolean;
};

type PolymorphicContainerProps<T extends React.ElementType> =
  ContainerOwnProps<T> &
    Omit<React.ComponentPropsWithoutRef<T>, keyof ContainerOwnProps<T>>;

type PolymorphicRef<T extends React.ElementType> =
  React.ComponentPropsWithRef<T>['ref'];

type ContainerComponent = <T extends React.ElementType = 'div'>(
  props: PolymorphicContainerProps<T> & {
    ref?: PolymorphicRef<T>;
  },
) => React.ReactElement | null;

const maxWidthMap = {
  sm: 'max-w-screen-sm', // 640px
  md: 'max-w-screen-md', // 768px
  lg: 'max-w-screen-lg', // 1024px
  xl: 'max-w-screen-xl', // 1280px
  '2xl': 'max-w-screen-2xl', // 1536px
  '3xl': 'max-w-[1920px]',
  '4xl': 'max-w-[2560px]',
  '5xl': 'max-w-[3840px]',
  '6xl': 'max-w-[5120px]',
  '7xl': 'max-w-[7680px]',
  full: 'max-w-full',
};

const ContainerInner = (
  props: ContainerOwnProps<any> & { [key: string]: any },
  ref: any,
) => {
  const {
    as,
    children,
    className = '',
    maxWidth = '7xl',
    centerContent = false,
    ...restProps
  } = props;

  const Component = as || 'div';

  const maxWidthClass = maxWidthMap[maxWidth];
  const centerClass = centerContent ? 'flex flex-col items-center' : '';
  const containerClasses =
    `mx-auto px-4 sm:px-6 lg:px-8 ${maxWidthClass} ${centerClass} ${className}`.trim();

  return (
    <Component ref={ref} className={containerClasses} {...restProps}>
      {children}
    </Component>
  );
};

const Container = React.forwardRef(
  ContainerInner,
) as unknown as ContainerComponent & {
  displayName: string;
};

Container.displayName = 'Container';

export { Container };
export type { PolymorphicContainerProps, ContainerOwnProps };
