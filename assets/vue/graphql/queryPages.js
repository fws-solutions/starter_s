export const queryPages = () => `{
    pages(first: 50) {
        nodes {
            title
            id
            guid
        }
    }
}`;
