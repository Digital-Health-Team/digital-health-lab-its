import React from 'react';
import { cn } from '@/Core/Utils/utils';

// ============================================
// TEXT COMPONENT (p, span)
// ============================================

type TextVariant = 'default' | 'lead' | 'large' | 'small' | 'muted';
type ElementType = 'p' | 'span' | 'div';

interface TextOwnProps {
  as?: ElementType;
  variant?: TextVariant;
  children: React.ReactNode;
  className?: string;
}

// Variant styles
const textVariants: Record<TextVariant, string> = {
  default: 'text-base leading-relaxed text-foreground',
  lead: 'text-xl leading-relaxed text-muted-foreground',
  large: 'text-lg leading-relaxed text-foreground',
  small: 'text-sm leading-relaxed text-muted-foreground',
  muted: 'text-sm leading-relaxed text-muted-foreground',
};

type TextProps = TextOwnProps &
  Omit<React.ComponentPropsWithRef<'p'>, keyof TextOwnProps>;

const TextInner = (props: TextProps, ref: React.Ref<HTMLElement>) => {
  const { as, variant = 'default', children, className, ...restProps } = props;

  // Determine component type
  const Component = (as || 'p') as React.ElementType;

  return (
    <Component
      ref={ref}
      className={cn(textVariants[variant], className)}
      {...restProps}
    >
      {children}
    </Component>
  );
};

const Text = React.forwardRef(TextInner) as React.ForwardRefExoticComponent<
  TextProps & React.RefAttributes<HTMLElement>
>;

Text.displayName = 'Text';

export { Text };
export type { TextProps, TextVariant };
