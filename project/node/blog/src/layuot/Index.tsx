import React from "react";
import { Layout, Menu, Breadcrumb } from "antd";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import {
  DesktopOutlined,
  PieChartOutlined,
  FileOutlined,
  TeamOutlined,
  UserOutlined,
} from "@ant-design/icons";
import Article from "./../page/article/Index";
import Edit from "./../page/article/edit";

const { Header, Content, Footer, Sider } = Layout;
const { SubMenu } = Menu;

interface state {
  collapsed: boolean | undefined;
}

export default class LayoutBase extends React.Component {
  public state: state;
  constructor(props: {} | Readonly<{}>) {
    super(props);
    this.state = {
      collapsed: false,
    };
    this.onCollapse = this.onCollapse.bind(this);
  }

  onCollapse(collapsed: boolean) {
    this.setState({ collapsed });
  }

  render() {
    return (
      <Router>
        <Layout style={{ minHeight: "100vh" }} id="components-layout-demo-side">
          <Sider
            collapsible
            collapsed={this.state.collapsed}
            onCollapse={this.onCollapse}
          >
            <div className="logo" />
            <Menu theme="dark" defaultSelectedKeys={["1"]} mode="inline">
              <Menu.Item key="1" icon={<PieChartOutlined />}>
                Option 1
              </Menu.Item>
              <Menu.Item key="2" icon={<DesktopOutlined />}>
                Option 2
              </Menu.Item>
              <SubMenu key="sub1" icon={<UserOutlined />} title="User">
                <Menu.Item key="3">
                  <Link to="/article">Tom</Link>
                </Menu.Item>

                <Menu.Item key="4">
                  <Link to="/id">Bill</Link>
                </Menu.Item>

                <Menu.Item key="5">Alex</Menu.Item>
              </SubMenu>
              <SubMenu key="sub2" icon={<TeamOutlined />} title="Team">
                <Menu.Item key="6">Team 1</Menu.Item>
                <Menu.Item key="8">Team 2</Menu.Item>
              </SubMenu>
              <Menu.Item key="9" icon={<FileOutlined />}>
                Files
              </Menu.Item>
            </Menu>
          </Sider>
          <Layout className="site-layout">
            <Header className="site-layout-background" style={{ padding: 0 }} />
            <Content style={{ margin: "0 16px" }}>
              <Breadcrumb style={{ margin: "16px 0" }}>
                <Breadcrumb.Item>User</Breadcrumb.Item>
                <Breadcrumb.Item>Bill</Breadcrumb.Item>
              </Breadcrumb>
              <div
                className="site-layout-background"
                style={{ padding: 24, minHeight: 360 }}
              >
                <Switch>
                  <Route path="/article">
                    <Article />
                  </Route>
                  <Route path="/id">
                    <Edit />
                  </Route>
                </Switch>
              </div>
            </Content>
            <Footer style={{ textAlign: "center" }}>
              Ant Design ©2018 Created by Ant UED
            </Footer>
          </Layout>
        </Layout>
      </Router>
    );
  }
}
