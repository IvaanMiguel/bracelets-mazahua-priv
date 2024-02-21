export interface User {
  username: string
  email: string
  email_verified_at: string
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User
  }
}

export interface MenuButton {
  icon: string
  label: string
  routeName: string
  component: string
}
