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

export type Link = {
  active: boolean,
  label: string,
  url: string | null
}

export interface Pagination<T> {
  current_page: number
  data: Array<T>
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: Array<Link>
  next_page_url: string | null
  path: string,
  per_page: number,
  prev_page_url: string | null
  to: number
  total: number
}

export interface MenuButton {
  icon: string
  label: string
  routeName: string
  component: string
}
