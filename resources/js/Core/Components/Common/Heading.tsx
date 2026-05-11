import React from 'react';
import { cn } from '@/Core/Utils/utils';

// ============================================
// HEADING COMPONENT (h1, h2, h3, h4, h5, h6)
// ============================================

type HeadingLevel = 1 | 2 | 3 | 4 | 5 | 6;
type HeadingElement = 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6';
type HeadingVariant = 'default' | 'display' | 'title' | 'subtitle' | 'section';

interface HeadingOwnProps {
  as?: HeadingElement;
  level?: HeadingLevel;
  variant?: HeadingVariant;
  children: React.ReactNode;
  className?: string;
}

// Variant styles
const headingVariants: Record<HeadingVariant, string> = {
  default: 'font-semibold text-foreground',
  display: 'text-5xl md:text-6xl font-bold tracking-tight text-foreground',
  title: 'text-3xl md:text-4xl font-bold text-foreground',
  subtitle: 'text-xl md:text-2xl font-semibold text-muted-foreground',
  section: 'text-2xl font-semibold text-foreground',
};

// Default sizes per level (jika variant = default)
const headingSizes: Record<HeadingLevel, string> = {
  1: 'text-4xl md:text-5xl',
  2: 'text-3xl md:text-4xl',
  3: 'text-2xl md:text-3xl',
  4: 'text-xl md:text-2xl',
  5: 'text-lg md:text-xl',
  6: 'text-base md:text-lg',
};

type HeadingProps = HeadingOwnProps &
  Omit<React.ComponentPropsWithRef<'h1'>, keyof HeadingOwnProps>;

const HeadingInner = (
  props: HeadingProps,
  ref: React.Ref<HTMLHeadingElement>,
) => {
  const {
    as,
    level = 1,
    variant = 'default',
    children,
    className,
    ...restProps
  } = props;

  // Determine component type
  const Component = as || (`h${level}` as HeadingElement);

  // if variant is default, use size according to level
  const variantClass =
    variant === 'default'
      ? `${headingVariants.default} ${headingSizes[level]}`
      : headingVariants[variant];

  return (
    <Component ref={ref} className={cn(variantClass, className)} {...restProps}>
      {children}
    </Component>
  );
};

const Heading = React.forwardRef(
  HeadingInner,
) as React.ForwardRefExoticComponent<
  HeadingProps & React.RefAttributes<HTMLHeadingElement>
>;

Heading.displayName = 'Heading';

export { Heading };
export type { HeadingProps, HeadingLevel, HeadingVariant };
