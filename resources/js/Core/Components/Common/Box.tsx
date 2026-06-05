import React from 'react';

// ============================================
// POLYMORPHIC BOX COMPONENT (div, section, etc)
// ============================================

type BoxOwnProps<T extends React.ElementType> = {
  as?: T;
  children?: React.ReactNode;
  className?: string;
};

type PolymorphicBoxProps<T extends React.ElementType> = BoxOwnProps<T> &
  Omit<React.ComponentPropsWithoutRef<T>, keyof BoxOwnProps<T>>;

type PolymorphicRef<T extends React.ElementType> =
  React.ComponentPropsWithRef<T>['ref'];

type BoxComponent = <T extends React.ElementType = 'div'>(
  props: PolymorphicBoxProps<T> & {
    ref?: PolymorphicRef<T>;
  },
) => React.ReactElement | null;

const BoxInner = (props: any, ref: any) => {
  const { as, children, className, ...restProps } = props;

  const Component = as || 'div';

  return (
    <Component ref={ref} className={className} {...restProps}>
      {children}
    </Component>
  );
};

const Box = React.forwardRef(BoxInner) as unknown as BoxComponent & {
  displayName: string;
};

Box.displayName = 'Box';

export { Box };
export type { PolymorphicBoxProps, BoxOwnProps };
